<?php
namespace Main\View;

use Main\App\App                     as App,
    Main\Conf\Conf                   as Conf,
    Main\Event\Event                 as Event,
    Main\View\Module\Module          as Module,
    Main\Parameter\Parameter         as Parameter,
    Main\View\Structure\Structure    as Structure,
    Main\View\Module\ModuleControler as ModuleControler;

Final Class View extends ModuleControler {

    private static $_oStructure = null;

    private static $_cTranslate = null;
    private static $_cScript    = null;
    private static $_cStyle     = null;

    /**
     * [_appendModule description]
     * @param  [type] $aSection [description]
     * @return [type] [description]
     */
    private static function _appendModule( &$aSection)
    {
        foreach (self::$_aModuleAppend as $sSection => $aModule) {

            if ( array_key_exists( $sSection, $aSection)) {
                foreach ( $aModule as $sSrc) {
                    $aSection[$sSection]['modules'][] = array('src'=>$sSrc);
                }
            }

        }
    }

    /**
     * [_appendModule description]
     * @param  [type] $aSection [description]
     * @return [type] [description]
     */
    private static function _prependModule( &$aSection)
    {
        foreach (self::$_aModulePrepend as $sSection => $aModule) {

            if ( array_key_exists( $sSection, $aSection)) {
                foreach ( $aModule as $sSrc) {
                    array_unshift( $aSection[$sSection]['modules'], array('src'=>$sSrc));
                }
            }

        }
    }

    private static function _treatResource( $aFile, $oloader, $fAction)
    {
        if( !is_array( $aFile)) return '';

        $aScript   = array_unique( $aFile);
        $oJsLoader = App::getLoader( $oloader);
        $aFile     = array();

        foreach ( $aScript as $sPathJs) {
            $sPath = $oJsLoader->load( $sPathJs);
            if ( $sPath != '') {
                $aFile[] = $sPath;
            }
        }

        return $fAction( $aFile);
    }

    /**
     * [translate description]
     * @param  [type] $sString [description]
     * @param  [type] $mValue  [description]
     * @return [type] [description]
     */
    private static function translate( $sString, $mValue = null)
    {
        return ( !is_null( $fAction = self::$_cTranslate))?
                    $fAction( $sString, $mValue):
                    $sString;
    }

    /**
     * [setTranslateMethod description]
     * @param [type] $fAction [description]
     */
    public static function setTranslateMethod( $fAction)
    {
        self::$_cTranslate = $fAction;
    }

    /**
     * [setTranslateMethod description]
     * @param [type] $fAction [description]
     */
    public static function setScriptMethod( $fAction)
    {
        self::$_cScript = $fAction;
    }

    /**
     * [setTranslateMethod description]
     * @param [type] $fAction [description]
     */
    public static function setStyleMethod( $fAction)
    {
        self::$_cStyle = $fAction;
    }

    /**
     * [setLoader description]
     */
    public static function setLoader( $aLoader)
    {
        self::$_aLoader  = $aLoader;
    }

    /**
     * [make description]
     * @param  [type] $sPathStructure [description]
     * @return [type] [object]
     */
    public static function make( $sPathStructure = '')
    {
        $aStructure = explode('.', $sPathStructure, 2) + array( 1=> NULL);
        $sPath = App::getLoader( self::$_aLoader['structure'])->load( $aStructure[0]);

        return self::$_oStructure = new Structure( $sPath, $aStructure[1]);
    }

    /**
     * [loadScript description]
     * @return [type] [string]
     */
    public static function loadScript()
    {
        $aJsFile =  self::$_oStructure->getData('js');
        return self::_treatResource(
            $aJsFile,
            self::$_aLoader['script'],
            self::$_cScript);
    }

    /**
     * [loadScript description]
     * @return [type] [string]
     */
    public static function loadStyle()
    {
        $aCssFile =  self::$_oStructure->getData('css');
        return self::_treatResource(
            $aCssFile,
            self::$_aLoader['style'],
            self::$_cStyle);
    }

    /**
     * [getSection description]
     * @param  [type] $sName [description]
     * @return [type] [description]
     */
    public static function section( $sName)
    {
        $sResult = '';
        if ( array_key_exists( $sName, self::$_aModule)) {

            foreach (self::$_aModule[$sName] as $oModule) {

                if ( is_readable( $sPath = $oModule->getPath())) {

                    ob_start();
                    include( $sPath);
                    $sResult .= ob_get_clean();
                    unset( $oModule);

                }
            }

            unset( self::$_aModule[$sName]);
        }

        return $sResult;
    }

    /**
     * [response description]
     * @return [type] [description]
     */
    public static function response()
    {
        $sResult = '';

        if ( is_null( self::$_oStructure)) {
            return false;
        }

        $sTemplate = self::$_oStructure->getData('template');

        //récuperation du layout HTML qui appelle les sections par la suite
        $sPath     = App::getLoader( self::$_aLoader['template'])->load( $sTemplate);

        $aSection  = self::$_oStructure->getData('sections');

        self::_appendModule( $aSection);
        self::_prependModule( $aSection);

        self::_computeSection( $aSection);

        if ( is_readable( $sPath)) {
            ob_start();
            include( $sPath);
            $sResult = ob_get_clean();
        }

        return $sResult;
    }
}
