<?php

namespace Main\Conf;

use Core\Dao\Dao as Dao;

Final class Conf
{
    /** @var array [description] */
    private static $_aConf = array();

    /**
     * [__construct description]
     * @param [type] $sEnv [description]
     */
    public static function addSetting( $sPathConf)
    {
        $sExt                        = pathinfo( $sPathConf, PATHINFO_EXTENSION);
        $aConfSetting                = array();
        $aConfSetting[$sExt]['file'] = $sPathConf;

        if ( !is_readable( $sPathConf)) {
            return false;
        }

        $oDaoDefault  = Dao::getInstance( $aConfSetting);

        self::$_aConf = array_merge( self::$_aConf, $oDaoDefault->read());
    }

    /**
     * [__get description]
     * @param  [type] $sName [description]
     * @return [type] [description]
     */
    public static function get($sName)
    {
        list($sSection, $sVar) = explode('.', $sName) + array(1=>NULL);

        if ( array_key_exists( $sSection, self::$_aConf)) {
            if ( is_null($sVar)) {
                return  self::$_aConf[$sSection];
            } else {
                return ( array_key_exists( $sVar, self::$_aConf[$sSection]))?
                     self::$_aConf[$sSection][$sVar] :
                     NULL;
            }
        } else {
            return array();
        }
    }
}
