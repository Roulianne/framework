<?php

namespace Main\View\Module;

use Main\App\App                     as App,
    Main\View\Module\Module          as Module,
    Main\View\Stock\StockController  as StockController,
    Main\View\Module\MultiModule     as MultiModule;

class ModuleControler
{
    protected static $_aLoader    = array(
                                        'structure' => 'structure',
                                        'module'    => 'module',
                                        'template'  => 'template',
                                        'script'    => 'script',
                                        'style'     => 'style',
                                    );

    protected static $_aModule = array();

    protected static $_aModuleAppend  = array();
    protected static $_aModulePrepend = array();

    /**
     * [_setOption description]
     * @return [type] [description]
     */
    private static function _setOption( $sPathModule, $sKey, $mValue)
    {
        self::_queryFind( $sPathModule, function ( $sSection, $sModule) {})->$sKey = $mValue;;
    }

    /**
     * [_query CONSTRUCTION VIA CONTROLLER]
     * @param  [type] $sPathModule [description]
     * @param  [type] $callBack    [description]
     * @return [type] [description]
     */
    private static function _queryFind( $sPathScope, $cCallBack)
    {
        $aModule     = array();
        $aPathModule = (array) explode( StockController::$sADD, $sPathScope);

        foreach ( $aPathModule as  $sPathScopeUniq) {
            list( $sSection, $sModule) = StockController::readScopeSyntax( trim( $sPathScopeUniq));
            $cCallBack( $sSection, $sModule);
        }

        return StockController::scope( $sPathScope);
    }

    /**
     * [_computeModule CONSTRUCTION VIA JSON]
     * @param  [type] $aInfo [description]
     * @return [type] [description]
     */
    private static function _computeModule( $sSection, $sModule, $aInfo)
    {
        if ( array_key_exists( 'src', $aInfo) AND $sModule = $aInfo['src']) {

            if( array_key_exists( 'isHideIf', $aInfo) AND self::_isHide( $aInfo['isHideIf'])){
                return false;
            }

            $sModule .= (array_key_exists('ref', $aInfo))? StockController::$sREF.$aInfo['ref'] : '';

            $aInfo['_section'] = $sSection;

            $oModule = self::_makeModule( $sModule, $sSection);

            $oModule->mergeParams( StockController::find( $aInfo))
                    ->mergeParams( $aInfo);

            if ( !$oModule->__disable__) {
                self::$_aModule[$sSection][$sModule] = $oModule;
            }
        }

    }

    /**
     * [_isHide description]
     * @param  [type]  $sFunction [description]
     * @return boolean            [description]
     */
    private static function _isHide( $sFunction){


        $sFunction = strtolower( $sFunction);

        switch ( $sFunction) {

            case 'webrequest':

                return self::_isWebRequest();
                break;

            default:

                return false;
                break;
        }

    }

     /**
     * [_isWebRequest description]
     * @return boolean [description]
     */
    protected static function _isWebRequest(){
      return isset( $_SERVER['HTTP_USER_AGENT']);
    }


    /**
     * [_addModule description]
     * @param [type] $aSection [description]
     */
    protected static function _computeSection( $aSection)
    {
        foreach ( (array) $aSection as $sSection => $aInfoSection) {

            if ( array_key_exists( 'modules', $aInfoSection) AND is_array( $aInfoSection['modules'])) {

                if( array_key_exists( 'isHideIf', $aInfoSection) AND self::_isHide( $aInfoSection['isHideIf'])){
                    continue;
                }

                foreach ( $aInfoSection['modules'] as $aInfo) {

                    if ( array_key_exists( 'src', $aInfo) AND $sModule = $aInfo['src']) {
                        self::_computeModule( $sSection, $sModule, $aInfo);
                    }
                }
            }
        }

    }

    /**
     * [_makeModule description]
     * @param  [type] $sModule [description]
     * @return [type] [description]
     */
    protected static function _makeModule( $sModule, $sSection = '*')
    {
        $sModule = ( strpos( $sModule, StockController::$sREF) !== false)? strstr( $sModule, StockController::$sREF, true): $sModule;

        $sPath = App::getLoader( self::$_aLoader['module'])->load( $sModule);

        return new Module( $sPath);
    }

    /**
     * [disable description]
     * @param  [type] $sPathModule [description]
     * @return [type] [description]
     */
    public static function disable( $sPathModule)
    {
        self::_setOption( $sPathModule, '__disable__', true);

        return true;
    }

    /**
     * [disable description]
     * @param  [type] $sPathModule [description]
     * @return [type] [description]
     */
    public static function active( $sPathModule)
    {
        self::_setOption( $sPathModule, '__disable__', false);

        return true;
    }

    /**
     * [append description]
     * @param  [type] $sPathModule [description]
     * @return [type] [description]
     */
    public static function append( $sPathModule)
    {
        return self::_queryFind( $sPathModule, function ( $sSection, $sModule) {
            self::$_aModuleAppend[$sSection][] = $sModule;
        });
    }

    /**
     * [append description]
     * @param  [type] $sPathModule [description]
     * @return [type] [description]
     */
    public static function prepend( $sPathModule)
    {
        return self::_queryFind( $sPathModule, function ( $sSection, $sModule) {
            if( !array_key_exists( $sSection, self::$_aModulePrepend))
                self::$_aModulePrepend[$sSection] = array();
            array_unshift( self::$_aModulePrepend[$sSection], $sModule);
        });
    }

    /**
     * [assign description]
     * @param  [type] $sPathModule [description]
     * @param  [type] $aContent    [description]
     * @return [type] [description]
     */
    public static function scope( $sPathModule)
    {
        return self::_queryFind( $sPathModule, function ( $sSection, $sModule) {});
    }
}
