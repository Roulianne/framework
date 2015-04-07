<?php
namespace Main\View\Stock;

use Main\View\Stock\StockController as StockController;

class Stock
{
    private $_sPathScope = '*';
    private $_aParams    = array();

    /**
     * [__construct description]
     * @param string $sPathScope [description]
     */
    public function __construct($sPathScope = '*')
    {
        $this->_sPathScope = $sPathScope;
    }

    /**
     * [getData description]
     * @return [type] [description]
     */
    public function getData()
    {
        return $this->_aParams;
    }

    /**
     * [__get description]
     * @param  [type] $sKey [description]
     * @return [type] [description]
     */
    public function __get($sKey)
    {
        /*
        if ( !array_key_exists($sKey, $this->_aParams)) {
            return null;
        }
        */

        list( $sSection, $sModule) = StockController::readScopeSyntax($this->_sPathScope);
        $aData = StockController::find( array( '_section'=>$sSection,'src'=>$sModule));

        if ( !array_key_exists($sKey, $aData)) {
            return null;
        }

        return $aData[$sKey];
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
