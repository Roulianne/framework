<?php

namespace Main\Builder\Helper;

use Main\Builder\Helper\Field as Field;

class Table{

	/**
	 * [$_aSchema description]
	 * @var array
	 */
	private $_aSchema  = array();

	private $_sPrefix  = null;

	private $_sReferer = '';

	private $_sTable   = '';

	private $_aAttribute = null;

	/**
	 * [$_aField description]
	 * @var array
	 */
	private $_aField  = array();

	/**
	 * [__construct description]
	 * @param array $aField [description]
	 */
	function __construct( $aField = array()){
		if( count( $aField)>0){
			$aFirst = current( $aField);
			$this->_sTable = $aFirst['TABLE_NAME'];
			$this->_sBase  = $aFirst['TABLE_SCHEMA'];
		}
		$this->_aSchema = $aField;
		$this->_setField();
	}

	/**
	 * [_setField description]
	 */
	private function _setField(){
		if( !empty( $this->_aSchema)){
			foreach ($this->_aSchema as $sField => $aInfo) {
				$sField = strtolower( $sField);
				$this->_aField[ $sField] = new Field( $aInfo);
			}
		}
	}

	/**
	 * [_findPrefix description]
	 * @param  [type] $aElement [description]
	 * @return [type]           [description]
	 */
	private function _findPrefix( $aElement){
		$iCount       = count( $aElement);
		$sStartPrefix = $aElement[0];

		for( $i = 1;  $i < $iCount ; $i++){

			$sPrefix         = '';
			$m               = 0;
			$aElementCurrent = str_split( $aElement[$i]);
			$aStartPrefix    = str_split( $sStartPrefix);
			$iCountPrefix    = count( $aStartPrefix);
			$iCountCurrent   = count( $aElementCurrent);
			$iMinCount       = min( $iCountPrefix, $iCountCurrent);

			while( $m < $iMinCount AND
				   $aStartPrefix[$m] == $aElementCurrent[$m]
				  ){

				$sPrefix .= $aStartPrefix[$m];
				$m++;
			}
			$sStartPrefix = $sPrefix;
		}

		return $sStartPrefix;
	}

	/**
	 * [getPrefix description]
	 * @return [type] [description]
	 */
	public function getPrefix(){
		if( is_null( $this->_sPrefix)){
			$aField  = array_keys( $this->_aField);
			$sPrefix = $this->_findPrefix( $aField);
			$this->_sPrefix = $sPrefix;
		}
		return $this->_sPrefix;
	}

	/**
	 * [getReferer description]
	 * @return [type] [description]
	 */
	public function getReferer(){
		if( $this->_sReferer == ''){
			foreach( $this->getField() as $oField){
				if( $oField->isPrimary()){
					return $oField;
				}
			}
		}
		return $this->_sReferer;
	}

	/**
	 * [getName description]
	 * @return [type] [description]
	 */
	public function getName(){
		return $this->_sTable;
	}

	/**
	 * [getInfo description]
	 * @return [type] [description]
	 */
	public function getReplace(){
		return array(
				'{MODEL-NAME}'      => ucfirst( $this->getName()),
				'{MODEL-TYPE}'      => $this->getName(),
				'{MODEL-PREFIX}'    => $this->getPrefix(),
				'{MODEL-REFERER}'   => $this->getReferer()->name,
				'{MODEL-STRUCTURE}' => $this->getStringStructure(),
		);
	}

	/**
	 * [getStructure description]
	 * @return [type] [description]
	 */
	public function getStringStructure(){
		$aList   = $this->getListField();
		$sResult = "\n";

		foreach( $aList as $sChamp){
			$sResult .= "\t\t\t\t'$sChamp' => ".$this->$sChamp->getStringInfo().",\n\n";
		}

		return $sResult;
	}

	/**
	 * [getListField description]
	 * @return [type] [description]
	 */
	public function getListField(){

		if( is_null($this->_aAttribute)){
			if( ($sPrefix = $this->getPrefix()) != ""){

				$this->_aAttribute = array_map( function( $sField) use ( $sPrefix){
						return str_replace( $sPrefix, '', $sField);
					}
					,array_keys( $this->_aField));

			}else{

				$this->_aAttribute = array_keys( $this->_aField);
			}

		}

		return $this->_aAttribute;
	}

	/**
	 * [getListField description]
	 * @return [type] [description]
	 */
	public function getField(){
		return $this->_aField;
	}

	/**
	 * [__get description]
	 * @param  [type] $sField [description]
	 * @return [type]         [description]
	 */
	public function __get( $sField){
		$sField = str_replace( $this->getPrefix(), '', $sField);
		$sField = strtolower( $this->getPrefix() . $sField);
		if( array_key_exists( $sField, $this->_aField)){
			return $this->_aField[ $sField];
		}
	}

}
