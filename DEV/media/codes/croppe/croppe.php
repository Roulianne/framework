<?php

use Image\Image\Image          as Image,
	  Main\App\App               as App,
    Main\Route\Route           as Route,
    Core\Header\Header         as Header,
    Main\Controller\Controller as Controller,

    Image\Point\Point           as Point,
    Image\Color\Color           as Color,
    Image\Box\Box               as Box;


$oImage        = new Image();
$bFilter       = false;
$sFilterName   = '';

if( !is_null( $sEffect = Controller::getQuery('effect'))){
    $sFilterName = $sEffect.'_';
    $bFilter     = true;
}

$sImageCurrent = Controller::getQuery('file').".".Route::get()->get('display');

$oBox          = new Box( Controller::getQuery('width'), Controller::getQuery('height'));
$oPoint        = new Point( Controller::getQuery('x'), Controller::getQuery('y'));

$sPathMedia    = App::get('pathDest').$sFilterName."croppe-".Controller::getQuery('x').Controller::getQuery('y').Controller::getQuery('width').Controller::getQuery('height')."_".$sImageCurrent;

if( !is_file( $sPathMedia)){

  $oEffect = $oImage->open( App::get('pathSrc').$sImageCurrent)
                     ->crop( $oPoint, $oBox)
                     ->effect();

  if($bFilter){

    if( strtolower($sEffect) == 'blur'){
        $oEffect->gaussian(20);
    }else if( strtolower($sEffect) == 'grey'){
        $oEffect->grayscale();
    }else if( strtolower($sEffect) == 'nega'){
        $oEffect->negative();
    }else{
        $oEffect->sharpen();
    }

  }else{
    $oEffect->sharpen();
  }

  $oEffect->stopEffect()
          ->save( $sPathMedia, App::get('option-save'));

}else{
    $sLastDate = date ("F d Y H:i:s.", filemtime( $sPathMedia));
    Header::setLastModified( $sLastDate);
}

App::set('path-media', $sPathMedia);
