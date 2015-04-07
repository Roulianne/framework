<?php
namespace Main\Route;

use Core\Request\Request        as Request;

Final Class Route {

    /** @var [type] [description] */
    private static $_oRequest = null;

    /** @var array [description] */
    private static $_aMethod  = array( 'get', 'post', 'session', 'put');

    /**
     * [_init description]
     * @return [type] [description]
     */
    private static function _init()
    {
        if ( is_null( self::$_oRequest)) {
            self::$_oRequest = Request::getInstance();
        }
    }

    /**
     * [setDefault description]
     * @param array $aDefault [description]
     */
    public static function setDefault(array $aDefault)
    {
        self::_init();
        self::$_oRequest->setDefaultGet( $aDefault);
    }

    /**
     * [post description]
     * @return [type] [description]
     */
    public static function post()
    {
        self::_init();

        return self::$_oRequest->getPost();
    }

    /**
     * [post description]
     * @return [type] [description]
     */
    public static function put()
    {
        self::_init();

        return self::$_oRequest->getPut();
    }

    /**
     * [post description]
     * @return [type] [description]
     */
    public static function delete()
    {
        self::_init();

        return self::$_oRequest->getGet();
    }

    /**
     * [get description]
     * @return [type] [description]
     */
    public static function get()
    {
        self::_init();

        return self::$_oRequest->getGet();
    }

    /**
    * [session description]
    * @return [type] [description]
    */
    public static function session()
    {
        self::_init();

        return self::$_oRequest->getSession();
    }

    /**
    * [getRequest description]
    * @return [type] [description]
    */
    public static function getRequest()
    {
        return self::$_oRequest;
    }

}
