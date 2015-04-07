<?php
namespace Core\Dao\Php;

use Core\Dao\Php\Accessible as Accessible,
    Core\Rest\Client        as Client;

class ApiDao implements Accessible
{

    private $_aWhere      = array();
    private $_aParameters = array();

    private $_oClient     = null;

    public  $TYPE         = 'API';

    /**
     * [__construct description]
     * @param [type] $sDsn
     * @param [type] $sUsername
     * @param [type] $sPassword
     * @param array  $aOptions
     */
    public function __construct( $aParams){

        if ( empty( $aParams)) {
            throw new Exception("Empty params");
        } else {

            $this->_sRootUrl       = rtrim( $aParams['root'], '/').'/';

            $this->_aApiLog        = $aParams['log'];
            $this->_aParameters    = $aParams['parameters'];

            $this->_treatPattern( $aParams['pattern']);

            $this->_oClient        = Client::getInstance();
        }
    }

    /**
     * [_getCondition description]
     * @param  [type] $sKey [description]
     * @return [type] [description]
     */
    private function _getCondition($sKey = NULL)
    {
       $mValue = NULL;

       if ($sKey == NULL) {
            $mValue = $this->_aWhere;
       } else {
            $mValue = ( array_key_exists( $sKey, $this->_aWhere))? $this->_aWhere[$sKey] : NULL;
       }

       return $mValue;
    }

    /**
     * [_treatPattern description]
     * @param  [type] $aPattern [description]
     * @return [type]           [description]
     */
    private function _treatPattern( $aPattern){

        foreach ($aPattern as $sPattern) {
            preg_match_all( '`\[:(.+):]`U', $sPattern, $aMatches);

            $aData = array();

            $aData['pattern'] = $sPattern;
            $aData['params']  = array_combine($aMatches[1], $aMatches[0]);

            $this->_aPatternUrl[] = $aData;
        }

        usort( $this->_aPatternUrl, function( $a, $b){
            return count( $b['params']) - count( $a['params']);
        });

        return $this;
    }

    /**
     * [_buildUrl description]
     * @return [type] [description]
     */
    private function _getdUrl( $aData = array()){
       $aWhere = $this->_getCondition();
       $aWhere += $this->_aParameters;

       $sUrl   = '';
       foreach ($this->_aPatternUrl as $aPattern) {
            if( count( array_diff( array_keys( $aPattern['params']), array_keys( $aWhere)))==0){
                $aWhere = array_map(
                    function( $mValue){
                        if( is_array( $mValue)){
                            return implode('/', array_values( $mValue));
                        }
                        return $mValue;
                    },
                    $aWhere
                );
                $sUrl = str_replace( $aPattern['params'], $aWhere, $aPattern['pattern']);
                break;
            }
       }

      return $this->_sRootUrl . $sUrl;
    }

    /**
     * [read description]
     * @param  [type] $sTable
     * @param  [type] $aWhere
     * @return [type]
     */
    public function read( $aSelect = array())
    {
        $this->_oClient->setUrl( $this->_getdUrl());
        $aResponse = $this->_oClient->get();
        return json_decode( $aResponse['content'], true);
    }

    /**
     * [create description]
     * @param  [type] $sTable
     * @param  array  $aData
     * @return [type]
     */
    public function create(array $aData)
    {
        $this->_oClient->setUrl( $this->_getdUrl());
        $aResponse = $this->_oClient->post( $aData);
        return json_decode( $aResponse['content'], true);
    }

    /**
     * [update description]
     * @param  [type] $sTable
     * @param  array  $aData
     * @param  array  $aWhere
     * @return [type]
     */
    public function update(array $aData){
        $this->_oClient->setUrl( $this->_getdUrl());
        $aResponse = $this->_oClient->post( $aData);
        return json_decode( $aResponse['content'], true);
    }

    /**
     * [delete description]
     * @param  [type] $sTable
     * @param  array  $aWhere
     * @return [type]
     */
    public function delete(){
        $this->_oClient->setUrl( $this->_getdUrl());
        $aResponse = $this->_oClient->delete( $aData);
        return json_decode( $aResponse['content'], true);
    }

    /**
     * [getStructure description]
     * @param  object $oElment [description]
     * @return [type]          [description]
     */
    public function options( $oElement)
    {
        $this->_oClient->setUrl( $this->_getdUrl());
        $aResponse = $this->_oClient->options();
        return json_decode( $aResponse['content'], true);
    }

    /**
     * [setCondition description]
     * @param [type] $aWhere [description]
     */
    public function setCondition(array $aWhere)
    {
        if (is_array( $aWhere)) {
            $this->_aWhere =  $aWhere;
        }

        return $this;
    }
}
