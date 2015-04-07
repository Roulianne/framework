<?php

namespace Core\Dao;

use Core\Dao\Php\MysqlDao  as MysqlDao;

/**
* This is the description for my class.
*
* @class Dao
* @constructor
*/
class Dao
{
    private static $_instance = array();

    private function __construct() {}

    /**
     * @method setDefaultLayer description
     * @param Array $mParams
     */
    private static function _buildLayer($mParams)
    {
        $sType = strtolower( key($mParams) );

        switch ($sType) {
            default:
            case 'ini':
            case 'xml':
            case 'json':
            case 'php':
            case 'csv':
            case 'api':
                $sTypeFormat = ucfirst($sType);
                $sClass      = "Core\Dao\Php\\$sTypeFormat".'Dao';
                $oDao        = new $sClass( $mParams[$sType] );
                break;
            case 'sql':
            case 'mysql':
                $oDao = new MysqlDao( $mParams[$sType]);
                break;
                //
        }

        return $oDao;
    }

    /**
     * @method _buildNameReferer description
     * @param  Array $mParams
     * @return String
     */
    private static function _buildNameReferer($mParams)
    {
        $sDaoReferer = md5( strtolower( serialize( $mParams)));

        return $sDaoReferer;
    }

    /**
     * @method getInstance description
     * @param  Array  $mParams
     * @return Object
     */
    public static function getInstance($mParams)
    {
        $sDaoReferer = self::_buildNameReferer( $mParams );

        if ( !isset( self::$_instance[$sDaoReferer])) {
            self::$_instance[$sDaoReferer] = self::_buildLayer( $mParams );
        }

        return self::$_instance[$sDaoReferer];
    }
}
