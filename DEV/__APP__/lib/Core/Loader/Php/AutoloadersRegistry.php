<?php

namespace Core\Loader\Php;

use \Exception as Exception,
    Core\Loader\Php\Autoloading as Autoloading;

require_once __DIR__.DS.'Autoloading.php';

class AutoloadersRegistry
{
    private static $_oFallBack = NULL;

    /**
     * [setFallBack description]
     * @param Autoloading $oAutoloading [description]
     */
    public static function setFallBack(Autoloading $oAutoloading)
    {
        if (NULL !== ($oCurrent = self::$_oFallBack)) {
            spl_autoload_unregister( array( $oCurrent, 'autoload'));
        }

        spl_autoload_register(
            array( self::$_oFallBack = $oAutoloading, 'autoload'),
            NULL,
            FALSE
        );

        return $oCurrent;
    }

    /**
     * [has description]
     * @param  Autoloading $oAutoloading [description]
     * @return boolean     [description]
     */
    public static function has(Autoloading $oAutoloading)
    {
        return in_array(
            array( $oAutoloading, 'autoload'),
            (array) spl_autoload_functions( )
        );
    }

    /**
     * [prepend description]
     * @param  Autoloading $oAutoloading [description]
     * @return [type]      [description]
     */
    public static function prepend(Autoloading $oAutoloading)
    {
        if (self::$_oFallBack === $oAutoloading) {
            throw new Exception;
        }

        if (self::has( $oAutoloading)) {
            self::remove( $oAutoloading);
        }

        spl_autoload_register(
            array( $oAutoloading, 'autoload'),
            NULL,
            TRUE
        );
    }

    /**
     * [append description]
     * @param  Autoloading $oAutoloading [description]
     * @return [type]      [description]
     */
    public static function append(Autoloading $oAutoloading)
    {
        if (self::$_oFallBack === $oAutoloading) {
            throw new Exception;
        }

        if (self::has( $oAutoloading)) {
            self::remove( $oAutoloading);
        }

        (NULL !== self::$_oFallBack)
            && spl_autoload_unregister( array( self::$_oFallBack, 'autoload'));

        spl_autoload_register( array( $oAutoloading, 'autoload'), NULL, FALSE);

        (NULL !== self::$_oFallBack)
            && spl_autoload_register( array( self::$_oFallBack, 'autoload'), NULL, FALSE);
    }

    /**
     * [remove description]
     * @param  Autoloading $oAutoloading [description]
     * @return [type]      [description]
     */
    public static function remove(Autoloading $oAutoloading)
    {
        if ($oAutoloading === self::$_oFallBack) {
            throw new Exception;
        }

        if (self::has( $oAutoloading)) {
            spl_autoload_unregister( array( $oAutoloading, 'autoload'));
        }
    }

    /**
     * [insertBefore description]
     * @param  Autoloading $oAutoloading [description]
     * @param  [type]      $oReference   [description]
     * @return [type]      [description]
     */
    public static function insertBefore(Autoloading $oAutoloading, Autoloading $oReference = NULL)
    {
        if (NULL === $oReference) {
            return self::append( $oAutoloading);
        }

        if ($oAutoloading === self::$_oFallBack) {
            throw new Exception;
        }

        if (! self::has( $oReference)) {
            throw new Exception;
        }

        if ($oAutoloading === $oReference) {
            throw new Exception;
        }

        $aAutoloaders = spl_autoload_functions( );
        $aRemoved			= array( );

        while ($oReference !== reset( $aAutoloaders)) {

            array_unshift( $aRemoved, $hAutoloader = array_shift( $aAutoloaders));

            spl_autoload_unregister( $hAutoloader);
        }

        self::prepend( $oAutoloading);

        foreach ($aRemoved as $hAutoloader) {
            spl_autoload_register( $hAutoloader, NULL, TRUE);
        }
    }

    /**
     * [insertAfter description]
     * @param  Autoloading $oAutoloading [description]
     * @param  [type]      $oReference   [description]
     * @return [type]      [description]
     */
    public static function insertAfter(Autoloading $oAutoloading, Autoloading $oReference = NULL)
    {
    }
}
