<?php

namespace Main\Translator;

use Core\Dao\Dao as Dao;

class Translator
{

    /** @var array [description] */
    private static $_aTranslate = array();
    private static $_oDao       = NULL;
    private static $_aData      = array();

    private static $_aDataReverse = array();

    /**
     * [_formatData description]
     * @return [type] [description]
     */
    private static function _formatData( $aData, &$aResult, $aOption = array('key', 'value'))
    {
        $sKey   = $aOption[0];
        $sValue = $aOption[1];

        foreach ($aData as $aInfo) {
            $aResult[$aInfo[$sKey]] = $aInfo[$sValue];
        }

        ksort($aResult);

    }

    /**
     * [_find description]
     * @param  [type] $sName    [description]
     * @param  [type] $aLexique [description]
     * @return [type] [description]
     */
    private static function _find($sName, $aLexique)
    {
        if ( array_key_exists($sName, $aLexique)) {
            return $aLexique[$sName];
        } else {
            return $sName;
        }
    }

    /**
     * [showLexique description]
     * @return [type] [description]
     */
    public static function showLexique()
    {
        return self::$_aData;
    }

    /**
     * [addLexique description]
     * @param [type] $sPathTraduction [description]
     */
    public static function addLexique($sPathTraduction, $bReversible = false)
    {
        $sExt                       = pathinfo( $sPathTraduction, PATHINFO_EXTENSION);
        $aConfTrad                  = array();
        $aConfTrad[$sExt]['folder'] =  $sPathTraduction;

        if ( !is_readable( $sPathTraduction)) {
            return false;
        }

        $oDaoDefault  = Dao::getInstance( $aConfTrad);

        if ($bReversible) {
            self::_formatData( $oDaoDefault->read(), self::$_aDataReverse, array('value', 'key'));
        }

        self::_formatData( $oDaoDefault->read(), self::$_aData);

    }

    /**
     * [transpose description]
     * @param  [type] $sName [description]
     * @return [type] [description]
     */
    public static function transpose($sName)
    {
        return self::_find( $sName, self::$_aDataReverse);
    }

    /**
     * [__get description]
     * @param  [type] $sName [description]
     * @return [type] [description]
     */
    public static function translate($sName, $mReplace = null)
    {
        $sValue = self::_find( $sName, self::$_aData);

        if ( !is_null($mReplace)) {
            if (is_array($mReplace)) {
                foreach ($mReplace as $sSearch => $sReplace) {
                    $sValue = str_replace( strtolower('[:'.$sSearch.':]'), $sReplace, $sValue);
                }
            }

            if (is_string($mReplace)) {
                $pattern = '/\[:([^:])*:\]/iU';
                $sValue = preg_replace($pattern, $mReplace, $sValue);
            }
        }

        return  $sValue;
    }
}
