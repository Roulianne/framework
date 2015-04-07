<?php

namespace Main\Builder\Helper;

use Main\Model\Model as Model,
    Main\Conf\Conf   as Conf,
    Main\Builder\Helper\Table as Table;

class Base extends Model
{
    /**
     * [$_sModel description]
     * @var string
     */
    protected $_sType = 'INFORMATION_SCHEMA.COLUMNS';

    /**
     * [$_aShema description]
     * @var [type]
     */
    private $_aShema = null;

    /**
     * [$_sReferer description]
     * @var string
     */
	protected $_sReferer = '';

    /**
     * [$_sPrefix description]
     * @var string
     */
    protected $_sPrefix  = '';

     /**
     * [_getConf description]
     * @return [type] [description]
     */
    protected function _getConf()
    {
        return Conf::get('builder');
    }

    /**
     * [_setTable description]
     * @param string $sBase [description]
     */
    public function buildSchema( $sBase = ''){

        if( is_null( $this->_aShema)){
            $aTable = $this->all();

            foreach ($aTable as $oTable) {

                $sBaseCurrent = $oTable->get('TABLE_SCHEMA');
                $sTable       = $oTable->get('TABLE_NAME');
                $sChamp       = $oTable->get('COLUMN_NAME');

                if( $sBaseCurrent == $sBase){
                    $this->_aShema[$sTable][$sChamp] = $oTable->getData();
                }
            }

            $this->_setData();

        }
    }

    /**
     * [getListTable description]
     * @return [type] [description]
     */
    public function getListTable(){
        return array_keys( $this->_aShema);
    }

    /**
     * [getTable description]
     * @return [type] [description]
     */
    public function getTable(){
        return $this->getData();
    }

    /**
     * [_setData description]
     */
    private function _setData(){
        $aData = array();
        foreach ($this->_aShema as $sTable => $aChamp) {
            $aData[$sTable] = new Table( $aChamp);
        }
        $this->setData( $aData);
    }

    /**
     * [getStructure description]
     * @return [type] [description]
     */
    public function getStructure(){

       return array();

    }
}
