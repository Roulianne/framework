<?php
namespace Core\Model\Order;

class Order
{
    /**
     * [$_oModel description]
     * @var [type]
     */
    private $_oModel    =   NULL;

    /**
     * [$position description]
     * @var integer
     */
    public $position    =   0;

    /**
     * [__construct description]
     * @param [type] $oModel [description]
     */
    public function __construct($oModel = NULL)
    {
        if ( !is_null( $oModel)) {
            $this->_setModel( $oModel);
        }
    }

    /**
     * [_setModel description]
     * @param [type] $oModel [description]
     */
    private function _setModel($oModel)
    {
        $this->_oModel = $oModel;

        return $this;
    }

    /**
     * [_existValue description]
     * @param  [type] $sValue [description]
     * @return [type] [description]
     */
    private function _existValue($sValue)
    {
        $sMethod = substr( $sValue,1);

        $mResult = $this->getModel()->$sMethod;

        if ( is_null( $mResult) || empty( $mResult) || $mResult == '' || $mResult == false) {
            return '';
        }

        return '';
    }

    /**
     * [getModel description]
     * @return [type] [description]
     */
    public function getModel()
    {
        return $this->_oModel;
    }

    /**
     * [classname description]
     * @return [type] [description]
     */
    public function classname()
    {
        $oModel = $this->getModel();
        $sClass = $oModel->getModel();

        return $sClass;
    }

    /**
     * [__call description]
     * @param  string $sMethod [description]
     * @param  [type] $mArgs   [description]
     * @return [type] [description]
     */
    public function __call($sMethod = '', $mArgs = NULL)
    {
        $bPos = strpos($sMethod, '_');
        if( $bPos !== false)
            return $this->_existValue( $sMethod);

        return NULL;
    }

    /**
     * [__get description]
     * @param  string $sParam [description]
     * @return [type] [description]
     */
     public function __get($sParam = '')
     {
        $sParam = strtolower( $sParam);

        return $this->$sParam();
    }

    /**
     * [__set description]
     * @param string $sParam [description]
     * @param [type] $mValue [description]
     */
    public function __set($sParam = '', $mValue)
    {
        $sParam = strtolower( $sParam);

        return $this->$sParam($mValue);
    }
}
