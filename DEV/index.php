<?php
session_start();


//test pour function jaavscript sur un autre code
if( isset( $_GET['sleep'])){
  sleep( intval( $_GET['sleep']));
}

// INITIALISATION DES DIFFERENT NAMESPACE
  use Main\App\App                as App,
      Main\Conf\Conf              as Conf,
      Main\View\View              as View,
      Main\Route\Route            as Route,
      Main\Event\Event            as Event,
      Core\Header\Header          as Header,
      Main\Translator\Translator  as Translator,
      Main\Controller\Controller  as Controller;

// INITIALISATION DES CONSTANT
  define( 'DEBUG'     , true);
  define( 'PS'        , PATH_SEPARATOR);
  define( 'DS'        , DIRECTORY_SEPARATOR);
  define( 'PATH_ROOT' , __DIR__ . DS);
  define( 'PATH_APP'  , PATH_ROOT  . '__APP__'  . DS);
  define( 'PATH_CONF' , PATH_APP   . 'conf' . DS);

  define( 'PATH_LIB'  , PATH_APP   . 'lib'  . DS);
  define( 'PATH_BIN'  , PATH_APP   . 'bin'  . DS);

  define( 'PATH_MOD'  , PATH_BIN   . 'models'  . DS);
  define( 'PATH_ORD'  , PATH_BIN   . 'orders'  . DS);

  define( 'PATH_CORE' , PATH_LIB   . 'Core' . DS);

  if( DEBUG)
    ini_set('display_errors', 'on');

// CHARGEMENT DES LIBRAIRIES
  require_once(
    implode(
      DS,
      array(
        'root'        => PATH_APP,
        'lib_current' => '_Lib.phar',
      )
    )
  );

// RECUPERATION DES CONFIGURATION POUR L'ENSEMBLE DU SITE
  Conf::addSetting( PATH_CONF.'conf.ini');
  // Conf::addSetting( PATH_CONF.'conf-api.ini');

// CONFIGURATION DE L'URL PAR DEFAULT

  Route::setDefault( array(
              'query'  => 'home',
              'display'=> 'html',
              'lang'   => 'fr_FR',
         ));

// MISE EN PLACE DES VARIABLES DE L'APP

  $sLang = ( strpos( $s = Route::get()->get('lang'), '_') !== false)? $s : $s.'_'.strtoupper( $s);// arbitraire pour le moment

  App::set( 'space', 'public');
  App::set( 'lang' , $sLang);
  App::set( 'domain_root' , Conf::get('app.http_root'));

  require_once( PATH_APP.'_bootstrap.php');

// EXECUTION DES CONTROLLEURS
  Controller::setQuery( Route::get()->get('query'));

  Controller::then(':model/:ref', function( $sValue, $sOther){
      Controller::addCode( 'details');
  })->with('ref', '([0-9]*)');

  Controller::then('home', function(){
      Controller::addCode( 'home');
  });

  Controller::then(':code', function(){
    Header::setStatus(404);
    Header::exec();
    echo 'ERROR 404';
  });


  Controller::prependCode( 'global');

// LANCEMENT
  if ( count( $aError = Controller::exec()) == 0) {
    Header::setStatus(200);
    Header::setContentType('html');
    Header::exec();
    echo View::response();
  } else {
    Header::setStatus(404);
    Header::exec();
    echo 'ERROR 404 : '.implode(', ',$aError);//erreur 404
  }
