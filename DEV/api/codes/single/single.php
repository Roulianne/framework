<?php

use Main\App\App               as App,
    Main\Route\Route           as Route,
    Main\Controller\Controller as Controller;

$aData =  Route::get()->getData();

$sMethod = Route::getRequest()->getMethod();

$sRef      = Controller::getQuery('ref');
$sModel    = ucfirst( Controller::getQuery('model'));
$oElements = new $sModel( $sRef);

switch( $sMethod){
  case 'POST':
        $aData     = Route::post()->getData();
        /*$oElements = new Article( $aData);
        $aData     = array('statue'=>'success', 'message'=>'Article ajouter correctement');*/
    break;

  case 'GET':
	    $aData = $oElements->reveal();
    break;

  case 'DELETE':
	    $aData = $oElements->delete();
	    $aData = array('statue'=>'success', 'message'=>'Article supprimer correctement');
  break;

  case 'OPTIONS':
      $aData = $oElements->options();
  break;

  default:
    $aData = array('statue'=>'error', 'message'=>'Methode non reconnue pour ce format d\'url');
    break;
}



App::set('data', array( $aData));
