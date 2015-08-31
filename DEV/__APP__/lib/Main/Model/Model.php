<?php

namespace Main\Model;

use Core\Dao\Dao                as Dao,
    Core\Model\Model            as ModelCore,
    Main\Conf\Conf              as Conf,
    Main\Model\Stock\StockModel as StockModel;

class Model extends ModelCore
{
    private  $_aOption  = array();

    /** @var array [description] */
    protected $_aSelect = array();
    protected $_aError  = array();

    private $_aFilter   = null;

    /**
     * [isValid description]
     * @param  [type]  $aData [description]
     * @return boolean [description]
     */
    protected function _isValid($aData)
    {
        $bValid = true;
        $aInput = $aData;

        $this->_aError = array();

        if ( count( $this->_getFilter())>0) {

            $aInput = filter_var_array( $aData, $this->_getFilter());

            foreach ($aInput as $sKey => $sValue) {
                if ( is_null( $sValue)) {
                    $this->_aError[$sKey] = 'required';
                    $bValid = false;
                }

                if ($sValue == false) {
                    $this->_aError[$sKey] = 'not conform';
                    $bValid = false;
                }
            }

            $aInput = array_merge($aData, $aInput);
        }
        return array( 'valid' => $bValid, 'data' => $aInput);

    }

    /**
     * [_getConf description]
     * @return [type] [description]
     */
    private function _getFilter()
    {
        if( is_null( $this->_aFilter)){

            $aStruture      = $this->getStructure();

            $this->_aFilter = array();

            foreach( $aStruture as $sChamp => $aInfo){
                if( is_array( $aInfo) && array_key_exists( 'filter', $aInfo)){
                    $this->_aFilter[$sChamp] = $aInfo['filter'];
                }
            }
        }

        return $this->_aFilter;
    }

    /**
     * [_getConf description]
     * @return [type] [description]
     */
    protected function _getConf()
    {
        return Conf::get('model');
    }

    /**
     * [_buildDao description]
     * @return [type] [description]
     */
    protected function _buildDao()
    {
        $oDaoPdo = Dao::getInstance( $this->_getConf());

        return $oDaoPdo;
    }

    /**
     * [_returnObject description]
     * @param  [type] $aResult [description]
     * @return [type] [description]
     */
    protected function _returnObject( $aResult)
    {
        $aResultObject = array();
        $sClassName    = get_class($this);

        foreach ($aResult as $aResultInfo) {

            $oElement = new $sClassName();
            $oElement->setOption( $this->_aOption)
                     ->setData( $aResultInfo);

            StockModel::addElement( $oElement, $this->getType());
            $aResultObject[] = $oElement;
        }


        return $aResultObject;
    }

     /**
     * [_getOption description]
     */
    protected function _getOption( $sKey = ''){
        if( $sKey !== '' && array_key_exists( $sKey , $this->_aOption)){
            return $this->_aOption[$sKey];
        }

        return null;
    }

    /**
     * [getDAO description]
     * @return [type] [description]
     */
    protected function getDAO(){
        return $this->_oDao;
    }

    /**
     * [_treatBeforeInsert description]
     * @param  [type] $_aData [description]
     * @return [type]         [description]
     */
    protected function _treatBeforeInsert( $_aData){
        return $_aData;
    }

    /**
     * [_treatBeforeInsert description]
     * @param  [type] $_aData [description]
     * @return [type]         [description]
     */
    protected function _treatBeforeUpdate( $_aData){
        return $_aData;
    }

    /**
     * [_treatBeforeInsert description]
     * @param  [type] $_aData [description]
     * @return [type]         [description]
     */
    protected function _treatBeforeDelete(){}

    /**
     * [getDAOFormat description]
     * @return [type] [description]
     */
    public function getDAOFormat(){
        return $this->getDAO()->TYPE;
    }

    /**
     * [getRefere description]
     * @return [type] [description]
     */
    public function getReferer(){
        return $this->_sPrefix.$this->_sRefererKey;
    }

    /**
     * [getRefererValue description]
     * @return [type] [description]
     */
    public function getRefererValue(){
        return $this->get($this->getReferer());
    }

    /**
     * [setSelect description]
     * @param [type] $aSelect [description]
     */
    public function setSelect( $aSelect = array())
    {
        $this->_aSelect = $aSelect;

        return $this;
    }

    /**
     * [getError description]
     * @return [type] [description]
     */
    public function getError()
    {
        return $this->_aError;
    }

    /**
     * [create description]
     * @param  array  $aValues [description]
     * @return [type] [description]
     */
    public function create( Array $aValues = array())
    {
        $aConditions =  array('type' =>$this->getType());

        if ( is_array( $aValid = $this->_isValid( $aValues)) AND $aValid['valid']) {

            $aData = $this->_treatBeforeInsert( $aValid['data']);

            $iLastId = $this->getDAO()->setCondition( $aConditions)
                                      ->create( $aData);

            if( isset( $aData['id'])){
                $aData['id'] = $iLastId;
            }

            $this->setData( $aData);
        }

        return $this;

    }

    /**
     * [_read description]
     * @param  [type] $mValue [description]
     * @param  string $sChamp [description]
     * @return [type] [description]
     */
    public function read($mValue, $sChamp = '')
    {

        if ($sChamp == '') {
            $aConditions =  array('type' =>$this->getType(),
                                  'where'=> array( $this->getReferer() => $mValue));

            if( StockModel::hasElement( $this->getType(), $mValue)){
                 return StockModel::getElement( $this->getType(), $mValue);
            }

        } else {

            $aConditions =  array('type' =>$this->getType(),
                                  'where'=> array( $sChamp => $mValue));
        }

        $aResult = $this->getDAO()->setCondition( $aConditions)
                                  ->read( $this->_aSelect);

        $this->setSelect();

        if ( !empty($aResult)) {
            $sKey = key( $aResult);
            $aData = ( $sKey == '0')? $aResult[0] : $aResult;

            $this->setData( $aData);
            StockModel::addElement( $this, $this->getType());
        }

        return true;
    }

    /**
     * [delete description]
     * @return [type] [description]
     */
    public function delete()
    {
        $aConditions =  array('type' =>$this->getType(),
                              'where'=> array( $this->getReferer() => $this->get($this->getReferer())),
                              'limit'=> array( '1'),
                              );

        $this->_treatBeforeDelete();

        $this->getDAO()->setCondition( $aConditions)
                    ->delete();

        $this->setData( array());

        return $this;
    }


    /**
     * [save description]
     * @return [type] [description]
     */
    public function save()
    {
        $aConditions =  array('type' =>$this->getType(),
                              'where'=> array( $this->getReferer() => $this->get($this->getReferer())),
                              'limit'=> array( '1'),
                              );

       $aValues = $this->getData();

       if ( is_array( $aValid = $this->_isValid( $aValues)) AND $aValid['valid']) {

            $aData = $this->_treatBeforeUpdate( $aValid['data']);

            $this->getDAO()->setCondition( $aConditions)
                           ->update( $aData);

            StockModel::setElement( $this, $this->getType());

            $this->setData( $aData);

        }

        return $this;
    }

    /**
     * [options description]
     * @param  object $oElement [description]
     * @return [type]           [description]
     */
    public function options() {

        $aConditions =  array('type' =>$this->getType(),
                              'where'=> array( $this->getReferer() => $this->get($this->getReferer())),
                              );

        return $this->getDAO()->setCondition( $aConditions)
                              ->options( $this);
    }

    /**
     * [setOption description]
     */
    public function setOption( $aOption = array()){

        if( count( $aOption)>0 ){
            $this->_aOption = $aOption;
        }

        return $this;
    }

    /**
     * [reveal description]
     * @return [type] [description]
     */
    public function reveal( $aData = array())
    {
        if ( empty( $aData)) {
            $aResult = $this->getData();
        } else {
            $aResult = $aData;
        }

        if( !is_null( $aFields = $this->_getOption('fields'))){

            $aFields = array_map( function( $sParam){
                return $this->_cleanAttr( $sParam);
            } , $aFields);

            $aResult = array_intersect_key( $aResult, array_fill_keys( $aFields, ''));

        }

        return (object) $aResult;

    }

    /**
     * [reveal description]
     * @return [type] [description]
     */
    public function revealTiny( $aData = array())
    {
        if ( empty( $aData)) {
            $aResult  = array();
            $sReferer = $this->getReferer();
            $aResult[$sReferer] = $this->get( $sReferer);

        } else {
            $aResult = $aData;
        }

        if( !is_null( $aFields = $this->_getOption('fields'))){

            $aResult = array_intersect_key( $aResult, array_fill_keys( $aFields, ''));

        }

        return (object) $aResult;

    }

    /**
     * [all description]
     * @param  string $str [description]
     * @return [type] [description]
     */
    public function all( $aParams = array())
    {
        $bScreen = false;

        if ( count($aParams) > 0) {

            $aConditions = array( 'type' => $this->getType());
            $aConditions += $aParams;
            $aResult =  $this->getDAO()->setCondition( $aConditions)
                                       ->read();
        } else {
            $bScreen = true;
            $aResult =  $this->screen();
        }
        return ($bScreen)? $aResult : $this->_returnObject( $aResult);
    }

    /**
     * [getStructure description]
     * @return [type] [description]
     */
    public function getStructure(){
        $aData = (array) $this->getData();
        return array_keys( $aData);
    }

    /**
     * [screen description]
     * @param  array  $aConditions [description]
     * @return [type] [description]
     */
    public function screen( $aConditions = array())
    {
        $aResultObject     = array();
        $aDefaultCondition = ( !isset( $aConditions['type']))? array('type'=>$this->getType()) : array('type'=>$aConditions['type']);
        $aConditions       = array_merge( $aConditions, $aDefaultCondition);

        $aResult = $this->getDAO()->setCondition( $aConditions)
                                  ->read( $this->_aSelect);


        return $this->_returnObject( $aResult);
    }

}
