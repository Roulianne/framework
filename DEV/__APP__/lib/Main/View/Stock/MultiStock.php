<?php
namespace Main\View\Stock;
/**
*
*/
class MultiStock
{
    private $_aStock = array();

    /**
     * [_findGlobal description]
     * @param  [type] $sKey   [description]
     * @param  [type] $sScope [description]
     * @return [type] [description]
     */
    public function __construct($aStock)
    {
        $this->_aStock = $aStock;
    }

    /**
     * [__set description]
     * @param [type] $sKey [description]
     */
    public function __set($sKey, $mValue)
    {
        foreach ($this->_aStock as $oStock) {
            $oStock->$sKey = $mValue;
        }

    }

}
