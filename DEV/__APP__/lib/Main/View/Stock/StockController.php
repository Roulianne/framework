<?php
namespace Main\View\Stock;

use Main\View\Stock\Stock  as Stock,
    Main\View\Stock\MultiStock  as MultiStock;

class StockController
{
    /** @var array [description] */
    private static $_aScope = array();
    private static $_aScopeREF = array();

    public static $sREF = '@';
    public static $sSRC = '.';
    public static $sADD = ',';

    /**
     * [_getUniqStock description]
     * @param  string $sPathScope [description]
     * @return [type] [description]
     */
    private static function _getUniqStock($sPathScope  = '*')
    {
        $sPathScope = trim($sPathScope);

        if ( ($sRef = self::_getRefStock( $sPathScope)) !== false) {
            return self::$_aScopeREF[$sRef];
        }

        list( $sSection, $sModule) = self::readScopeSyntax( $sPathScope);

        if( array_key_exists($sSection, self::$_aScope) AND
            array_key_exists($sModule, self::$_aScope[$sSection])){
            return self::$_aScope[$sSection][$sModule];
        } else {
            return self::$_aScope[$sSection][$sModule] = new Stock( $sPathScope);
        }
    }

    /**
     * [_getRefStock description]
     * @param  string $sPathScope [description]
     * @return [type] [description]
     */
    private static function _getRefStock($sPathScope  = '*')
    {
        if ( strpos($sPathScope, self::$sREF) !== false) {

            $sRef = strstr( $sPathScope, self::$sREF);

            if ( array_key_exists( $sRef, self::$_aScopeREF)) {

                self::$_aScopeREF[$sRef];
            } else {

                self::$_aScopeREF[$sRef] = new Stock( $sPathScope);
            }

            return $sRef;
        }

        return false;
    }

    /**
     * [_getMultiStock description]
     * @param  array  $aPathScope [description]
     * @return [type] [description]
     */
    private static function _getMultiStock( $aPathScope = array())
    {
        $aScope = array();

        foreach ($aPathScope as $sPathScope) {
            $aScope[] = self::_getUniqStock( $sPathScope);
        }

        return new MultiStock( $aScope);
    }

    /**
     * [_initPath description]
     * @param  [type] $aInfo [description]
     * @return [type] [description]
     */
    private static function _initPath($aInfo)
    {
        $aSection = array('*');
        $aModule  = array('*');

        if ( isset( $aInfo['_section'])) {
            array_unshift( $aSection, $aInfo['_section']);
        }

        if ( isset( $aInfo['src'])) {
            array_unshift( $aModule, $aInfo['src']);
        }

        return array( $aSection, $aModule);
    }

    /**Ò
     * [showAll description]
     * @return [type] [description]
     */
    public static function showAll()
    {
        return array( 'SCOPE' => self::$_aScope,
                      'REF' => self::$_aScopeREF);
    }

    /**
     * [scope description]
     * @param  string $sPathScope [description]
     * @return [type] [description]
     */
    public static function scope($sPathScope = '*')
    {
        if ( strpos( $sPathScope, self::$sADD) !== false) {
            $aPathScope = explode( self::$sADD, $sPathScope);

            return self::_getMultiStock( $aPathScope);
        } else {
            return self::_getUniqStock( $sPathScope);
        }
    }

    /**
     * [find description]
     * @param  array  $aInfo [attribut du module defni dans le json (src, _section, ref, ...)]
     * @return [type] [description]
     */
    public static function find( $aInfo = array())
    {
        list( $aSection, $aModule) = self::_initPath( $aInfo);

        $aData = array();

        if ( array_key_exists('ref', $aInfo)) {
            $sRef = self::$sREF.$aInfo['ref'];
            if ( array_key_exists($sRef, self::$_aScopeREF)) {
                $aData = self::$_aScopeREF[$sRef]->getData();
            }
        }

        foreach ($aSection as $sSection) {
            if ( !array_key_exists($sSection, self::$_aScope)) {
                continue;
            }

            foreach ($aModule as $sModule) {
                if ( !array_key_exists($sModule, self::$_aScope[$sSection])) {
                    continue;
                }
                $aData += self::$_aScope[$sSection][$sModule]->getData();
            }
        }

        return $aData;

    }

    /**
     * [clear description]
     * @return [type] [description]
     */
    public static function clear()
    {
        unset( self::$_aScope, self::$_aScopeREF);
    }

    /**
     * [readScopeSyntax description]
     * @param  [type] $sPathScope [description]
     * @return [type] [description]
     */
    public static function readScopeSyntax($sPathScope = '*')
    {
        if ( current( $aString = str_split( $sPathScope)) == self::$sSRC) {
            $sPathScope = '*'.$sPathScope;
        }

        if ( end( $aString) == self::$sSRC) {
            $sPathScope .= '*';
        }

        return  explode( self::$sSRC, trim( $sPathScope), 2) + array( 1=> '*');
    }

}
