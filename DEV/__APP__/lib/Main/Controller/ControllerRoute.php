<?php

namespace Main\Controller;

//[ Ajout : 2015-06-30 10:46:16 ( julien Jamet ) ]
class ControllerRoute{

    private $_sPattern  = '';
    private $_fCallable = null;

    private $_aMatch    = array();
    private $_aParams   = array();
    private $_aMatchKey = array();

    /**
     * [__construct description]
     * @param [type] $s [description]
     */
    function __construct( $sPattern, $fCallable){

        $this->_sPattern  = trim( $sPattern, '/');
        $this->_fCallable = $fCallable;
    }

    /**
     * [paramsMatch description]
     * @return [type] [description]
     */
    private function paramsMatch( $aMatch){

        $sKey = $aMatch[1];

        if( !isset( $this->_aParams[$sKey])){
            $this->_aParams[$sKey] = '[^/]+';
        }

        $this->_aMatchKey[] = $sKey;

        return '('.$this->_aParams[$sKey].')';
    }

    /**
     * [match description]
     * @param  [type] $sUrl [description]
     * @return [type]       [description]
     */
    public function match( $sUrl){

        $sUrl   = trim( $sUrl, '/');
        $sRegex = preg_replace_callback('#\[:([\w]+):\]#', array( $this, 'paramsMatch'), $this->_sPattern);

        $sNewRegex ="#^$sRegex$#i";

        if( !preg_match( $sNewRegex, $sUrl, $aMatch)){
            return false;
        }

        array_shift( $aMatch);
        $this->_aMatch = $aMatch;

        return true;

    }
    /**
     * [getParam description]
     * @return [type] [description]
     */
    public function getParam(){
        return array_combine( $this->_aMatchKey, $this->_aMatch);
    }

    /**
     * [with description]
     * @return [type] [description]
     */
    public function with( $sName, $sPattern){
        $this->_aParams[$sName] = str_replace( '(', '(?:', $sPattern);
        return $this;
    }

    /**
     * [call description]
     * @return [type] [description]
     */
    public function call( ){
        return call_user_func_array( $this->_fCallable, $this->_aMatch);
    }

}

