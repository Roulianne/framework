<?php

namespace Core\Loader\Php;

/* Déclaration des imports. */
use Core\Loader\Loader as Loader,
    Core\Loader\Php\Autoloading as Autoloading,
    Core\Loader\Php\AutoloadersRegistry as AutoloadersRegistry;

require_once( __DIR__.DS.'Autoloading.php');
require_once( __DIR__.DS.'..'.DS.'Loader.php');
require_once( __DIR__.DS.'AutoloadersRegistry.php');

class Library extends Loader implements Autoloading
{
    private static $_oAutoloader = NULL;

    /**
     * [getAutoloader description]
     * @return [type] [description]
     */
    public static function getAutoloader()
    {
        if (NULL === self::$_oAutoloader) {

            $sNamespace	= substr(__NAMESPACE__, 0, strpos( __NAMESPACE__, '\\'));
            $sPath		= substr( __DIR__, 0, - strlen( __NAMESPACE__));

            self::$_oAutoloader = (new self)->setOptions(array(
                    'pattern'	=> '[:path:]/[:namespace:]/[:resourceId:][:suffix:]',
                    'parameters'	=> array(
                            'namespace'	=> [$sNamespace],
                            'path'			=> [$sPath],
                            'suffix'		=> '.php',
                    )
            ));

            AutoloadersRegistry::setFallBack( self::$_oAutoloader);
        }

        return self::$_oAutoloader;
    }

    /**
     * [load description]
     * @param  [type] $sIncompleteName [description]
     * @return [type] [description]
     */
    public function load($sIncompleteName)
    {
        $sCompleteName = NULL;

        if (TRUE === parent::load( str_replace( '\\', '/', $sIncompleteName))) {
            foreach ((array) $this->_aOptions['parameters']['namespace'] as $sNamespace) {

                $sCurrent = preg_replace( '`\\\+`', '\\', "\\{$sNamespace}\\{$sIncompleteName}");

                if (class_exists( $sCurrent, FALSE)
                            || interface_exists( $sCurrent, FALSE)) {

                    $sCompleteName = $sCurrent;
                    break;
                }
            }
        }

        return $sCompleteName;
    }

    /**
     * [_load description]
     * @param  [type] $sUri [description]
     * @return [type] [description]
     */
    protected function _load($sUri)
    {
        require_once $sUri;
    }

    /**
     * [autoload description]
     * @param  [type] $sCompleteName [description]
     * @return [type] [description]
     */
    public function autoload($sCompleteName)
    {
        $bMustProcess	= FALSE;
        $aNamespaces	= $this->_aOptions['parameters']['namespace'];

        foreach ((array) $aNamespaces as $sNamespace) {

            if ( $sNamespace == '' || 0 === strpos( $sCompleteName, $sNamespace)) {
                $bMustProcess = TRUE;
                break;
            }
        }

        if ($bMustProcess) {

            //$this->_aOptions['parameters']['namespace'] = '';

            $this->load( $sCompleteName);

            $this->_aOptions['parameters']['namespace'] = $aNamespaces;
        }
    }
}
