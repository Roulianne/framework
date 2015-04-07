<?php

namespace Core\Log;

class Log
{

    private static $_fAddError = null;

    private static $_bInit     = false;
    /**
     * [init description]
     * @return [type] [description]
     */
    public static function init(){
        self::$_bInit = true;
        set_error_handler( array( 'self', 'write'), E_ALL);
    }

    /**
     * [getHandler description]
     * @param  [type] $fAddError [description]
     * @return [type]            [description]
     */
    public static function setHandler( $fAddError = null){
        if( !self::$_bInit){
            self::init();
        }
        self::$_fAddError = $fAddError;
    }

    /**
     * [write description]
     * @param  [type] $errno   [description]
     * @param  [type] $errstr  [description]
     * @param  [type] $errfile [description]
     * @param  [type] $errline [description]
     * @return [type]          [description]
     */
    public static function write( $iCode = 0, $sMessage = "", $sFile = "", $iLine = 0){

        $sSerial = md5( $iLine.$sMessage.$sFile);

        $sType   = 'inconnue';

        switch ( $iCode) {
            case E_USER_ERROR:
                    $sType = 'fatale';
                break;

            case E_USER_WARNING:
                    $sType = 'alerte';
                break;

            case E_USER_NOTICE:
                    $sType = 'avertissement';
                break;

            default:

                break;
        }


         $oError = (object) array(
            "serial"   => $sSerial,
            "type"     => $sType,
            "url"      => $_SERVER['REQUEST_URI'],
            "ligne"    => $iLine,
            "fichier"  => $sFile,
            "message"  => $sMessage,
            "code"     => $iCode,
            "status"   => 0,
            "creation" => date('Y-m-d H:i:s'),
        );

        if( !is_null( self::$_fAddError)){
            $sFunction = self::$_fAddError;
            $sFunction( $oError);
        }

    }
}


