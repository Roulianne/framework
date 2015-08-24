<?php

use Main\App\App               as App,
    Main\Parameter\Parameter           as Parameter,
    Main\Controller\Controller as Controller;

$aData   =  Parameter::get()->getData();

$sMethod = Parameter::getRequest()->getMethod();

$sRef    = Controller::getQuery('ref');

if( $sRef != 'none'){
  $oArticles = new Article( $sRef);
}else{
  $oArticles = new Article();
}


App::set('data', $oArticles->options());
