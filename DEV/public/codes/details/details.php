<?php

use Main\App\App               as App,
    Main\Conf\Conf             as Conf,
    Main\View\View             as View,
    Main\Route\Route           as Route,
    Main\Event\Event           as Event,
    Main\Translator\Translator as Translator,
    Main\Controller\Controller as Controller;



$sRef     = Controller::getQuery('ref');
$sModel   = ucfirst( Controller::getQuery('model'));

$oElement = new $sModel( $sRef);
$aElements = $oElement->all();
shuffle( $aElements);
array_splice( $aElements, 6);

View::scope( 'middle.description')->element = $oElement;
View::scope( 'middle.thumbnail')->elements  = $aElements;

View::make( 'general.'.strtolower( $sModel));
