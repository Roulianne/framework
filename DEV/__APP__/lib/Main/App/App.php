<?php
namespace Main\App;

use Core\Request\Request        as Request,
    Core\Dispenser\Dispenser    as Dispenser,
    Main\PathLoader\PathLoader  as PathLoader;

Final Class App {

    private static $_aVar    = array();

    private static $_aLoader = array();

    /**
     * [addLoader description]
     * @param string $path [description]
     */
    public static function addLoader(array $aConfValue, $sName = '')
    {
        $aConfValue['parameters'] += self::$_aVar;
        self::$_aLoader[$sName]    = (new PathLoader)->setOptions( $aConfValue);

        return self::$_aLoader[$sName];
    }

    /**
     * [addLoader description]
     * @param string $path [description]
     */
    public static function getLoader($sName = '')
    {
        return ( array_key_exists( $sName, self::$_aLoader))?
            self::$_aLoader[$sName]:
            false;
    }

    /**
     * [__set description]
     * @param [type] $sKey   [description]
     * @param [type] $mValue [description]
     */
    public static function set($sKey, $mValue)
    {
        self::$_aVar[$sKey] = $mValue;
    }

    /**
     * [__set description]
     * @param [type] $sKey   [description]
     * @param [type] $mValue [description]
     */
    public static function get($sKey)
    {
        return ( array_key_exists( $sKey, self::$_aVar))?
            self::$_aVar[$sKey]:
            false;
    }

}
