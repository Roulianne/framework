<?php

namespace Main\Controller;

use Main\App\App                    as App,
    Main\Conf\Conf                  as Conf,
    Main\Controller\ControllerRoute as ControllerRoute;

class Controller
{
    private static $_aRoute      = array();
    private static $_sFolderRoot = 'code';
    private static $_aCode       = array();

    private static $_aPath       = array();

    private static $_sQuery      = '';

    private static $_aQuery      = array();

    private static $_aRegexLib   = array(
            "*" => "(.+)?",
            "I" => "([0-9]+)?",
            "S" => "([a-zA-Z]+)?",
        );


    /**
     * [_findRoute description]
     * @return [type] [description]
     */
    private static function _findRoute(){
        /*
            foreach( self::$_aRoute as $sRegex => $aParams){

                if( preg_match( "#{$aParams['regex']}#", self::$_sQuery, $aMatches) &&
                    count( $aMatches) == count( $aParams['params'])+1){

                    array_shift( $aMatches);

                    self::$_aQuery = array_combine( $aParams['params'], $aMatches);
                    $aParams['function']();
                    return true;
                }
            }
        */
        foreach( self::$_aRoute as $oRoute){

            if( $oRoute->match( self::$_sQuery)){
                self::$_aQuery = $oRoute->getParam();
                return $oRoute->call();
            }

        }

        return false;
    }

    /**
     * [query description]
     * @param  [type] $sKey [description]
     * @return [type]       [description]
     */
    public static function getQuery( $sKey = ''){

        if( array_key_exists( $sKey, self::$_aQuery)){
            return self::$_aQuery[$sKey];
        }

        return null;
    }

    /**
     * [addCode description]
     * @param [type] $sName [description]
     */
    public static function addCode($sName)
    {
        self::$_aCode[]   = $sName;
    }

    /**
     * [prepenCode description]
     * @param  [type] $sName [description]
     * @return [type] [description]
     */
    public static function prependCode($sName)
    {
        array_unshift( self::$_aCode, $sName);
    }

    /**
     * [level description]
     * @param  [type] $iInt [description]
     * @return [type] [description]
     */
    public static function level($iInt)
    {
        return( isset( self::$_aPath[$iInt]))?  self::$_aPath[$iInt] : null;
    }

    /**
     * [then description]
     * @param  [type] $sRegex [description]
     * @param  [type] $cCode  [description]
     * @return [type]         [description]
     */
    public static function then( $sRegex, $cCode){

        /*
            preg_match_all( '`\[:(.+):]`U', $sRegex, $aMatches);

            $aReplace  = array();
            $aDefaults = ( isset( $aMatches[1]))? $aMatches[1] : array();

            self::$_aRoute[$sRegex]['function'] = $cCode;
            self::$_aRoute[$sRegex]['params']   = array();

            foreach( $aDefaults as $i => $sParamRegex){
                $sCode  = '*';
                $sParam = $sParamRegex;

                if( strpos( $sParamRegex, '|')){
                    list( $sParam, $sCode) = explode('|', $sParamRegex);
                    $sCode = trim( $sCode);
                }

                self::$_aRoute[$sRegex]['params'][] = trim( $sParam);//$aMatches[0], '(.+)?'

                $sPartialRegex = ( array_key_exists( $sCode, self::$_aRegexLib))? self::$_aRegexLib[$sCode]: self::$_aRegexLib['*'];
                $sSearch                            = $aMatches[0][$i];
                $aReplace[ $sSearch]                = $sPartialRegex;
            }

            self::$_aRoute[$sRegex]['regex']    = str_replace( array_keys( $aReplace), array_values( $aReplace), $sRegex);
        */
        $oRoute = new ControllerRoute(  $sRegex, $cCode);

        self::$_aRoute[] = $oRoute;

        return $oRoute;
    }

    /**
     * [setQuery description]
     * @param string $sQuery [description]
     */
    public static function setQuery( $sQuery = ''){
        self::$_sQuery = $sQuery;
    }

    /**
     * [exec description]
     * @return [type] [description]
     */
    public static function exec()
    {
        $aError = array();
        self::_findRoute();

        foreach (self::$_aCode as $sController) {
          if ( is_readable( $sPathController = App::getLoader( self::$_sFolderRoot)->load( $sController))) {
            include( $sPathController);
          } else {
            $aError[] = $sController;
          }
        }

        return $aError;
    }

}
