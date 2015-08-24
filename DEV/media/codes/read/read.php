<?php

use Main\App\App               as App,
    Main\Parameter\Parameter           as Parameter,
    Core\header\Header         as Header,
    Main\Controller\Controller as Controller;


$sImageCurrent = Controller::getQuery('file').".".Parameter::get()->get('display');
$sPathMedia    = App::get('pathSrc').$sImageCurrent;

App::set('path-media', $sPathMedia);

if( is_readable( $sPathMedia)){
	$sLastDate = date ("F d Y H:i:s.", filemtime( $sPathMedia));
	//Header::setStatus(304);
	//Header::setLastModified( $sLastDate);
}
