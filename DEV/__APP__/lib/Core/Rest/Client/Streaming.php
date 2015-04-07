<?php

namespace Core\Rest\Client;


class Streaming
{
   protected $_url;
   protected $_aFiles    = array();


   /**
    * [__construct description]
    */
   public function __construct(){}

   /**
    * [_createContext description]
    * @param  [type] $pMethod  [description]
    * @param  [type] $aData [description]
    * @return [type]           [description]
    */
   protected function _createContext($pMethod, $aData = null){
     return NULL;
   }

   /**
    * [_makeUrl description]
    * @param  [type] $pParams [description]
    * @return [type]          [description]
    */
   protected function _makeUrl($pParams){
      return $this->_url
             .(strpos( $this->_url, '?') ? '' : '?') . http_build_query( $pParams);
   }

   /**
    * [_launch description]
    * @param  [type] $pUrl    [description]
    * @param  [type] $context [description]
    * @return [type]          [description]
    */
   protected function _launch ($pUrl, $context){
        return '';
   }

   /**
    * [addFile description]
    * @param [type] $sName     [description]
    * @param [type] $sPathFile [description]
    */
   public function addFile( $sName, $sPathFile){
        $this->_aFiles[$sName] = $sPathFile;
        return $this;
   }

   /**
    * [setUrl description]
    * @param [type] $pUrl [description]
    */
   public function setUrl ($pUrl){
      $this->_url = $pUrl;
      return $this;
   }

   /**
    * [read description]
    * @param  array  $pParams [description]
    * @return [type]          [description]
    */
   public function get ($aGetData = array()){
      return $this->_launch($this->_makeUrl( $aGetData),
                            $this->_createContext('GET'));
   }

   /**
    * [add description]
    * @param array $pPostParams [description]
    * @param array $aGetData  [description]
    */
   public function post ($pPostParams=array(), $aGetData = array()){
      return $this->_launch($this->_makeUrl( $aGetData),
                            $this->_createContext('POST', $pPostParams));
   }

   /**
    * [update description]
    * @param  [type] $aData   [description]
    * @param  array  $aGetData [description]
    * @return [type]             [description]
    */
   public function put ($aData = null, $aGetData = array()){
      return $this->_launch($this->_makeUrl( $aGetData),
                            $this->_createContext('PUT', $aData));
   }

   /**
    * [delete description]
    * @param  [type] $aData   [description]
    * @param  array  $aGetData [description]
    * @return [type]             [description]
    */
   public function delete ($aData = null, $aGetData = array()){
      return $this->_launch($this->_makeUrl( $aGetData),
                            $this->_createContext('DELETE'));
   }

   /**
    * [delete description]
    * @param  [type] $aData   [description]
    * @param  array  $aGetData [description]
    * @return [type]             [description]
    */
   public function options ($aData = null, $aGetData = array()){
      return $this->_launch($this->_makeUrl( $aGetData),
                            $this->_createContext('OPTIONS'));
   }
}
