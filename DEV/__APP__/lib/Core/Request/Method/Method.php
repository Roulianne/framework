<?php

namespace Core\Request\Method;

class Method
{
    /**
     * [$_sType description]
     * @var string
     */
    protected $_sType = '';

    /**
     * [$_aValue description]
     * @var array
     */
    protected $_aValue = array();

    /**
     * [$_bEmpty description]
     * @var boolean
     */
    protected $_bCanBeEmpty = true;

    /**
     * [__construct description]
     * @param array $aValue [description]
     */
    public function __construct(){}

    /**
     * [canBeEmpty description]
     * @return [type] [description]
     */
    public function beEmpty( $bBeEmpty = false){
        $this->_bCanBeEmpty = $bBeEmpty;
        return $this;
    }

    /**
     * [setDefault description]
     * @param array $aArray [description]
     */
    public function setDefault( $aArray = array())
    {

        $this->_aValue = array_merge( $aArray, $this->_aValue);

        return $this;
    }

    /**
     * [get description]
     * @param  string $sValue [description]
     * @return [type] [description]
     */
    public function get($sValue = '')
    {
        $sValue = strtolower($sValue);

        if (array_key_exists( $sValue, $this->_aValue)) {
            return $this->_aValue[$sValue];
        } else {
            return NULL;
        }
    }

    /**
     * [update description]
     * @param string $sKey   [description]
     * @param [type] $mValue [description]
     */
    public function update($sKey = '', $mValue = NULL)
    {
       if (array_key_exists( $sKey, $this->_aValue)) {
            $this->_aValue[$sKey] = $mValue;
        }

        return $this;
    }

    /**
     * [exist description]
     * @param string $sKey   [description]
     * @param [type] $mValue [description]
     */
    public function exist($sKey)
    {
        return array_key_exists( $sKey, $this->_aValue);
    }

    /**
     * [getData description]
     * @return [type] [description]
     */
    public function getData()
    {
        return $this->_aValue;
    }

    /**
     * [setData description]
     * @param array $aValue [description]
     */
    public function setData( $aValue = array()){

        if( empty( $aValue)) return $this;

        if( !$this->_bCanBeEmpty){
            $aValue = array_filter( $aValue, function( $sVar){
                return (!( $sVar == '' || is_null( $sVar)));
            });
        }

        $this->_aValue = array_merge( $this->_aValue, $aValue);

        return $this;
    }

    /**
     * [generateId description]
     * @return [type] [description]
     */
    public function generateId()
    {
        if ( count($this->getData())>0) {
            $aOut = array();
            foreach ( $this->getData() as $mValue) {
                if ( is_array( $mValue)) {
                    $aOut[]= implode( '+', $mValue);
                } else {
                    $aOut[]= $mValue;
                }
            }
            $aValues = array_values( $aOut);
        } else {
            $aValues = array('empty');
        }

        return implode( '-', $aValues);
    }

}
