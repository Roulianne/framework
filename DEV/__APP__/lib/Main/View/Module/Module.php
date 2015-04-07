<?php
namespace Main\View\Module;
/**
*
*/
class Module
{
    private $_sPath    = '';
    private $_sSection = '*';

    private $_aParams  = array();

    private $_aDefaultParams = array(
        '__disable__' => false,
        );

    private static $_aGlobalParams = array();

    /**
     * [__construct description]
     * @param [type] $sPath [description]
     */
    public function __construct($sPath = '')
    {
        $this->_sPath = $sPath;
    }

    /**
     * [setSection description]
     * @param [type] $sSection [description]
     */
    public function setSection($sSection)
    {
        self::$_aGlobalParams[$this->_sSection] = array();
        $this->_sSection = $sSection;

        return $this;
    }

    /**
     * [setSection description]
     * @param [type] $sSection [description]
     */
    public function setRef($sRef)
    {
        return $this;
    }

    /**
     * [setParams description]
     * @param [type] $aParams [description]
     */
    public function mergeParams($aParams)
    {
        $this->_aParams += $aParams;

        return $this;
    }

    /**
     * [getParams description]
     * @return [type] [description]
     */
    public function getParams()
    {
        return $this->_aParams;
    }

    /**
     * [getPath description]
     */
    public function getPath()
    {
        return $this->_sPath;
    }

    /**
     * [__get description]
     * @param  [type] $sKey [description]
     * @return [type] [description]
     */
    public function __get($sKey)
    {
        //find on Module
        if ( array_key_exists($sKey, $this->_aParams)) {
            return $this->_aParams[$sKey];
        }

        return ( array_key_exists( $sKey, $this->_aDefaultParams))?
                $this->_aDefaultParams[$sKey]:
                NULL;

    }

    /**
     * [__set description]
     * @param [type] $sKey [description]
     */
    public function __set($sKey, $mValue)
    {
            $this->_aParams[$sKey] = $mValue;
    }

}
