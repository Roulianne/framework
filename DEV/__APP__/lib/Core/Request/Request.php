<?php

namespace Core\Request;

use Core\Request\Method\Get     as Get,
    Core\Request\Method\Post    as Post,
    Core\Request\Method\Put     as Put,
    Core\Request\Method\Delete  as Delete,
    Core\Request\Method\Options as Options,
    Core\Request\Method\Session as Session;

class Request
{
    /**
     * [$_oInstance description]
     * @var [type]
     */
    private static $_oInstance   = NULL;

    /**
     * [$_oGet description]
     * @var [type]
     */
    private $_oGet     = NULL;

    /**
     * [$_oPost description]
     * @var [type]
     */
    private $_oPost    = NULL;

    /**
     * [$_oDelete description]
     * @var [type]
     */
    private $_oDelete    = NULL;

     /**
     * [$_oDelete description]
     * @var [type]
     */
    private $_oOptions    = NULL;

    /**
     * [$_oPut description]
     * @var [type]
     */
    private $_oPut    = NULL;

    /**
     * [$_oSession description]
     * @var [type]
     */
    private $_oSession = NULL;

    /**
     * [$_sMethod description]
     * @var [type]
     */
    private $_sMethod = NULL;

    /**
     * [__construct description]
     */
    private function __construct()
    {
        $this->_sMethod = $_SERVER['REQUEST_METHOD'];


        $aGet = filter_input_array(INPUT_GET);

        $oGet = new Get();
        $oGet->setData( $aGet);
        $this->_setGet($oGet);

        $aPost = filter_input_array(INPUT_POST);

        $oPost = new Post();
        $oPost->setData( $aPost);
        $this->_setPost($oPost);

        $oSession = new Session();
        $this->_setSession($oSession);

        $oDelete = new Delete();
        $oDelete->setData( $aGet);
        $this->_setDelete( $oDelete);

        $oOptions = new Options();
        $oOptions->setData( $aGet);
        $this->_setOptions( $oOptions);

        $aPut  = array();

        parse_str( file_get_contents( "php://input"), $aPut);

        $oPut = new Put();
        $oPut->setData( $aPut);
        $this->_setPut( $oPut);

    }

    /**
     * [initSet description]
     * @param [type] $oGet [description]
     */
    public function initGet( $aGet = array())
    {
        $oGet = new Get( $aGet);
        $this->_setGet($oGet);

        return $this;
    }

    /**
     * [_setGet description]
     * @param [type] $oGet [description]
     */
    private function _setGet($oGet)
    {
        $this->_oGet = $oGet;

        return $this;
    }

    /**
     * [_setPost description]
     * @param [type] $oPost [description]
     */
    private function _setPost($oPost)
    {
        $this->_oPost = $oPost;

        return $this;
    }

     /**
     * [_setDelete description]
     * @param [type] $oPost [description]
     */
    private function _setDelete($oDelete)
    {
        $this->_oDelete = $oDelete;

        return $this;
    }

    /**
     * [_setDelete description]
     * @param [type] $oPost [description]
     */
    private function _setOptions($oOptions)
    {
        $this->_oOptions = $oOptions;

        return $this;
    }

    /**
     * [_setPut description]
     * @param [type] $oPost [description]
     */
    private function _setPut($oPut)
    {
        $this->_oPut = $oPut;

        return $this;
    }

    /**
     * [_setSession description]
     * @param [type] $oSession [description]
     */
    private function _setSession($oSession)
    {
        $this->_oSession = $oSession;

        return $this;
    }

    /**
     * [getGet description]
     * @return [type] [description]
     */
    public function getGet()
    {
        return $this->_oGet;
    }

    /**
     * [getPost description]
     * @return [type] [description]
     */
    public function getPost()
    {
        return $this->_oPost;
    }

    /**
     * [getDelete description]
     * @return [type] [description]
     */
    public function getDelete()
    {
        return $this->_oDelete;
    }

    /**
     * [getPut description]
     * @return [type] [description]
     */
    public function getPut()
    {
        return $this->_oPut;
    }

    /**
     * [getSession description]
     * @return [type] [description]
     */
    public function getSession()
    {
        return $this->_oSession;
    }

    /**
     * [setDefaultGet description]
     * @param array $aArray [description]
     */
    public function setDefaultGet( $aArray = array())
    {
        $this->_oGet->setDefault( $aArray);

        return $this;
    }

    /**
     * [setDefaultPost description]
     * @param array $aArray [description]
     */
    public function setDefaultPost( $aArray = array())
    {
        $this->_oPost->setDefault( $aArray);

        return $this;
    }

    /**
     * [setDefaultSession description]
     * @param array $aArray [description]
     */
    public function setDefaultSession( $aArray = array())
    {
        $this->_oSession->setDefault( $aArray);

        return $this;
    }

    /**
     * [getRequestName description]
     * @return [type] [description]
     */
    public function getRequestName()
    {
        $sIdGet  = $this->getGet()->generateId();
        $sIdPost = $this->getPost()->generateId();

        $sCombId = $sIdGet.'_'.$sIdPost;

        return $sCombId;
    }

    /**
     * [getInstance description]
     * @return [type] [description]
     */
    public static function getInstance()
    {
        if (is_null(self::$_oInstance)) {
            self::$_oInstance = new Request();
        }

        return self::$_oInstance;

    }

    /**
     * [getMethod description]
     * @return [type] [description]
     */
    public function getMethod(){
        return $this->_sMethod;
    }

}
