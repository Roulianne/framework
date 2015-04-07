<?php

use Main\App\App               as App,
    Main\Route\Route           as Route,
    Main\Controller\Controller as Controller;

$aData   =  Route::get()->getData();

$sMethod = Route::getRequest()->getMethod();

$sRef    = Controller::getQuery('ref');

if( $sRef != 'none'){
  $oArticles = new Article( $sRef);
}else{
  $oArticles = new Article();
}


App::set('data', $oArticles->options());
