<?php

use Main\Parameter\Parameter    as Parameter,
    Core\Header\Header  as Header,
    Main\App\App        as App;

$sPathFile    = dirname(__FILE__).'/../../_tmp/';
$sPathFileTmp = $sPathFile.'thumb/';
$aOption      = array('quality' => 80);

App::set('pathSrc',      $sPathFile);
App::set('pathDest',     $sPathFileTmp);
App::set('option-save',  $aOption );

Header::setContentType( Parameter::get()->get('display'));