<?php

/***********************
 *     Load Libraries
 ***********************/

use Core\Loader\Php\Library             as Library,
    Core\Loader\Php\AutoloadersRegistry as Autoloaders;

require_once PATH_CORE .'Loader'.DS.'Php'.DS.'Library.php';

$oLoader = (new Library)->setOptions(array(
    'pattern'       => '[:path:]/[:namespace:]/[:resourceId:][:suffix:]',
    'parameters'    => array(
            'path'      => array( PATH_LIB, PATH_MOD, PATH_ORD),
            'namespace' => array( ''),
            'suffix'    => '.php',
    )
));

Autoloaders::setFallBack( $oLoader);
