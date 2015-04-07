<?php

use Main\App\App               as App,
    Main\Conf\Conf             as Conf,
    Main\View\View             as View,
    Main\Route\Route           as Route,
    Main\Event\Event           as Event,
    Main\Translator\Translator as Translator,
    Main\Controller\Controller as Controller;



$oShermarks = new Shermarks();
$aShermarks = $oShermarks->all();

View::scope( 'middle.thumbnail')->elements = $aShermarks;

View::make( 'general.home');


if(  App::get('dev')){
	Event::trigger('plus');
}