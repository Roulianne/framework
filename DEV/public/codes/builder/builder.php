<?php


use Main\App\App               as App,
    Main\Conf\Conf             as Conf,
    Main\View\View             as View,
    Main\Route\Route           as Route,
    Main\Event\Event           as Event,
    Main\Translator\Translator as Translator,
    Main\Controller\Controller as Controller,
    Main\Builder\Builder       as Builder;


$oBuilder = new Builder( 'noireplatine');

if( $oBuilder->generate()){
	echo 'files ok';
}else{
	echo '<pre>';
	print_r( $oBuilder->getError());
	echo '</pre>';
}
