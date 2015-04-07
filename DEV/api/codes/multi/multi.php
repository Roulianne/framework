<?php

use Main\App\App               as App,
    Main\Route\Route           as Route,
    Main\Controller\Controller as Controller;

$aData =  Route::get()->getData();

$sMethod = Route::getRequest()->getMethod();
$sModel = ucfirst( Controller::getQuery('model'));

switch( $sMethod){
  case 'POST':
        $aData     = Route::post()->getData();
        $oElements = new $sModel( $aData);
        $aData     = array('statue'=>'success', 'message'=> $sModel.' ajouter correctement');
    break;
  case 'GET':
    $oElements = new $sModel();

    $aData = array_map( function( $oModel){
    	return $oModel->reveal();
    }, $oElements->all());

    break;
  case 'OPTIONS':
  $oElements = new $sModel();
      $aData = $oElements->options();
  break;
  default:
    $aData = array('statue'=>'error', 'message'=>'Methode non reconnue pour ce format d\'url');
    break;
}



App::set('data', $aData);
