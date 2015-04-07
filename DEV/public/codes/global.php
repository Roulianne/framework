<?php

use Main\App\App             as App,
    Main\Conf\Conf           as Conf,
    Main\View\View           as View,
    Main\Route\Route         as Route,
    Main\Event\Event         as Event;


Event::addListener( 'sub', function( $sValue, $e){
  	View::scope('top.citation')->content = " ({$sValue})";

});


Event::addListener( 'plus', function(){
  	View::scope('top.menu')->titre .= "** ";

});