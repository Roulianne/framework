var gulp        = require('gulp');
var exec        = require('gulp-exec');
var zip         = require('gulp-zip');
var clean       = require('gulp-clean');

var rename      = require('gulp-rename');
var imagemin    = require('gulp-imagemin');
var htmlreplace = require('gulp-html-replace');
var uglify      = require('gulp-uglify');

var notify      = require('gulp-notify');

var gReplace    = require('gulp-replace');

var jshint      = require('gulp-jshint');
var recess      = require('gulp-recess');
/**
 * VAR
 */


/**
 * [getCurrentDate description]
 * @return {[type]} [description]
 */
var  getCurrentDate = function(){
	var oNow   = new Date();

	var sYear  = oNow.getFullYear();

	var sMonth = oNow.getMonth()+1;
	    sMonth = ( sMonth < 10)? '0' + sMonth : sMonth;

	var sDate  = ( oNow.getDate()  < 10)? '0' + oNow.getDate()  : oNow.getDate();

	return sName  = sYear+'-'+sMonth+'-'+sDate;
};

/** LIST TASK PACKAGE **/
gulp.task('lastUpdate', function(){
	return gulp.src('.')
		.pipe( exec('cd ./DEV/;find . -mtime -1 | cpio -pdm ../www/'));
});

gulp.task('sup_tmp',['lastUpdate'], function(){
	return gulp.src([
					'./www/_tmp/**/*'
				])
			   .pipe(clean());

});

gulp.task('zipUpdate', ['sup_tmp'], function(){
	var sName = 'PH-n_'+getCurrentDate();
	return gulp.src('./www/**/*')
		.pipe( zip( sName+'.zip'))
        .pipe( gulp.dest( './_packages/'));
});

gulp.task('default', ['zipUpdate'], function(){
	return gulp.src('./www', {read: false})
        .pipe(clean())
        .pipe( notify( 'Package créé avec succé'));
});


/** LIST TASK PROD **/
gulp.task('imagemin', function(){
	return gulp.src([ './DEV/**/*.jpg', './DEV/**/*.png', './DEV/**/*.gif'])
        .pipe(imagemin())
        .pipe(gulp.dest('PROD'));
});

gulp.task('copy', function(){
	return gulp.src(['./DEV/**/*', './DEV/.htaccess'])
			   .pipe(gulp.dest('PROD'));
});

gulp.task('clean', ['compress', 'imagemin'], function(){
	return gulp.src([
					'./PROD/__APP__/lib/Core',
					'./PROD/__APP__/lib/Image',
					'./PROD/__APP__/lib/Main',
					'./PROD/__APP__/conf/conf.ini',
					'./PROD/_tmp/**/*.jpg',
					'./PROD/_tmp/**/*.png'
				])
			   .pipe(clean());

});

gulp.task('htmlreplace',['clean'], function(){
	return gulp.src("./PROD/**/template/*.phtml")
	    .pipe(htmlreplace({
	       	'style'  : '<?php echo "" ;?>/css/min.css',
	        'script' : '<?php echo "" ;?>/js/min.js'
	    }))
	    .pipe(gulp.dest("./PROD/"));
});

gulp.task('compress',['copy'], function() {
  gulp.src('./PROD/**/js/**/*.js')
    .pipe(uglify({outSourceMap: true}))
    .pipe(gulp.dest('dist'))
});


gulp.task('prod',['htmlreplace'], function(){
	return gulp.src("./_ressources/_conf/conf.prod.ini")
    .pipe( rename('app/conf/conf.ini'))
    .pipe( gulp.dest("./PROD/"))
    .pipe( notify( 'Ficher pour prod OK'));
});

/** ExEMPLE **/

gulp.task('replace',function(){
	gulp.src("DEV/public/txet.json", {base: './'})
		.pipe( gReplace(/\[.*\]/g, 'julien'))
		.pipe( gulp.dest('./'));
});

gulp.task('jshint', function() {
  return gulp.src('./DEV/**/js/**/*.js')
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});

gulp.task('recess', function () {
    return gulp.src('src/app.css')
        .pipe(recess())
        .pipe(gulp.dest('dist'));
});

