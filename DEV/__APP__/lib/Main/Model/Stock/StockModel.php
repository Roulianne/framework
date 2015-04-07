<?php

namespace Main\Model\Stock;

use Core\Dao\Dao             as Dao,
    Core\Model\Model         as ModelCore,
    Main\Conf\Conf           as Conf;

class StockModel
{
    private static $_aStock  = array();

    /**
     * [addElement description]
     * @param [type] $oModel [description]
     * @param [type] $sType  [description]
     */
    public static function addElement( $oModel, $sType){

    	$sRefModel = $oModel->get($oModel->getReferer());
    	$sType     = strtolower( $sType);

    	if( !self::hasElement( $sType, $sRefModel)){
    		self::$_aStock[ $sType][ $sRefModel] = $oModel;
    	}
    }

    /**
     * [addElement description]
     * @param [type] $oModel [description]
     * @param [type] $sType  [description]
     */
    public static function setElement( $oModel, $sType){
    	$sRefModel = $oModel->get($oModel->getReferer());
    	$sType     = strtolower( $sType);

    	if( self::hasElement( $sType, $sRefModel)){
    		self::$_aStock[ $sType][ $sRefModel] = $oModel;
    	}
    }

    /**
     * [addElements description]
     * @param [type] $aModel [description]
     * @param [type] $sType  [description]
     */
    public static function addMultiElement( $aModel, $sType){
    	foreach ($aModel as $oModel) {
    		self::addElement( $oModel, $sType);
    	}
    }

    /**
     * [hasModel description]
     * @param  [type]  $sType     [description]
     * @param  [type]  $sRefModel [description]
     * @return boolean            [description]
     */
    public static function hasElement( $sType, $sRefModel){
    	if (array_key_exists( $sType, self::$_aStock)){
    		return array_key_exists( $sRefModel, self::$_aStock[$sType]);
    	}
    	return false;
    }

    /**
     * [getElement description]
     * @return [type] [description]
     */
    public static function getElement( $sType, $sRefModel){

    	if( self::hasElement( $sType, $sRefModel)){
    		return self::$_aStock[ $sType][ $sRefModel];
    	}

    	return null;
    }

}
