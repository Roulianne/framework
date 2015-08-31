<?php

namespace Core\Model;

use Core\Model\Php\AccessModel as AccessModel;

class Model implements AccessModel
{
    private $_oOrder        = NULL;
    private $_aOrderMethods = array();

    protected $_sType       = '';

    protected $_sPrefix     = '';

    protected $_oDao        = NULL;
    protected $_sRefererKey = 'id';
    protected $_oDatas      = NULL;
    protected $_aOversight  = array();

    /**
     * [__construct description]
     */
    public function __construct()
    {

        $aArgs = func_get_args();
        $this->_init( $aArgs);

        return $this;
    }

    /**
     * [_loadInteger description]
     * @param  integer $iId [description]
     * @return [type]  [description]
     */
    private function _load($mValue = NULL, $sType)
    {
        switch ( $sType) {
            case 'integer':
                $this->read( $mValue );
                break;

            case 'string':
                $this->read( $mValue , $this->_sPrefix.$this->_sRefererKey);
                break;

            case 'array':
                $this->create( $mValue);
                break;

            case 'object':
                $this->setData( $mValue);
                break;

        }

        return $this;
    }

    /**
     * [_loadClassFile description]
     * @return [type] [description]
     */
    private function _loadClassFile($sClassName, $sFolder = '')
    {
        $sClassFile = PATH_BIN . $sFolder . DS . $sClassName. '.php';
        if (is_readable( $sClassFile) ) {
            require_once $sClassFile;

            return true;
        } else {
            return false;
        }
    }

    /**
     * [_buildOrder description]
     * @return [type] [description]
     */
    private function _buildOrder()
    {
        $sModel = get_class($this);

        $sOrder = $sModel."Order";

        if ($this->_loadClassFile( $sOrder, 'orders')) {
            $oOrder = new $sOrder( $this);
            $this->_aOrderMethods = get_class_methods( $oOrder);
            return $oOrder;
        } else {
            return NULL;
        }
    }

    /**
     * [_init description]
     * @return [type] [description]
     */
    protected function _init( array $aArgs){
        $this->_oOrder    = $this->_buildOrder();
        $this->_oDao      = $this->_buildDao();

        if ( !empty( $aArgs)) {
            $sType = gettype( $aArgs[0]);

            if( isset( $aArgs[1]) ){
                $this->_sRefererKey = $aArgs[1];
            }


            $this->_load( $aArgs[0], strtolower( $sType));
        }
    }

    /**
     * [_cleanAttr description]
     * @param  [type] $sParam [description]
     * @return [type]         [description]
     */
    protected function _cleanAttr( $sParam){
        $sParam = str_replace($this->_sPrefix, '', $sParam);
        return $this->_sPrefix.$sParam;
    }

    /**
     * [_buildDao description]
     * @return [type] [description]
     */
    protected function _buildDao()
    {
        return NULL;
    }


    /**
     * [isExist description]
     * @return boolean [description]
     */
    public function isExist(){
        return ( count( $this->getData()) > 0);
    }

    /**
     * [setData description]
     * @param [type] $oDatas [description]
     */
    public function setData( $aDatas)
    {
        $this->_oDatas = (object) $aDatas;
        return $this;
    }

    /**
     * [getData description]
     * @return [type] [description]
     */
    public function getData( $sKey = '')
    {

        $_aDatas = (Array) $this->_oDatas;

        if( $sKey != ''){
            return $_aDatas[ $sKey];
        }else{
            return $_aDatas;
        }

    }

    /**
     * [getType description]
     * @return [type] [description]
     */
    public function getType()
    {
       return strtolower( $this->_sType);
    }



    /**
     * [Get description]
     * @param string $sParam [description]
     */
    public function get($sParam = '')
    {

        $sParam = $this->_cleanAttr( $sParam);

        if ( !is_null( $this->_oDatas)) {
            if ( isset(  $this->_oDatas->$sParam) || get_class($this->_oDatas) != 'stdClass') {
                $mResult =   $this->_oDatas->$sParam;
                if ( is_string( $mResult)) {
                    $mResult = stripslashes( $mResult);
                }

                return $mResult;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * [Set description]
     * @param string $sParam [description]
     * @param [type] $mValue [description]
     */
    public function set($sParam = '', $mValue)
    {
        $sParam = $this->_cleanAttr( $sParam);

        if ( isset( $this->_oDatas->$sParam)) {
            $this->_oDatas->$sParam = $mValue;
        } else {
            return false;
        }
    }

    /**
     * [__get description]
     * @param  [type] $sParam [description]
     * @return [type] [description]
     */
    public function __get($sParam = '')
    {
        $sParam = strtolower( $sParam);
        if ( $this->_oOrder != NULL && in_array(  $sParam, $this->_aOrderMethods)) {
            return $this->_oOrder->$sParam;
        } else {
            return $this->get( $sParam);
        }
    }

    /**
     * [__set description]
     * @param [type] $sParam [description]
     * @param [type] $mValue [description]
     */
    public function __set($sParam = '', $mValue)
    {

        $sParam = strtolower( $sParam);
        $this->set( $sParam, $mValue);

    }

    /** [create description] */
    public function create( Array $aValues = array()) {}

    /** [read description] */
    public function read($mValue, $sChamp = '') {}

    /** [delete description] */
    public function delete() {}

    /** [save description] */
    public function save() {}

   /** [screen description] */
    public function screen( $aConditions = array()) {}

    /** [options description] */
    public function options() {}
}
