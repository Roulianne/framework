<?php

namespace Main\Builder;

use Main\Builder\Helper\Base as Base;

class Builder{

	/**
	 * [$_oBase description]
	 * @var [type]
	 */
	private $_oBase = null;

	/**
	 * [$_aError description]
	 * @var array
	 */
	private $_aError = array();

	/**
	 * [$_sBase description]
	 * @var string
	 */
	private $_sBase  = '';

	/**
	 * [$_aTemplate description]
	 * @var array
	 */
	private $_aTemplate = array(
		'model' => array(
				"template"=>"<?php\n\nuse Main\Model\Model as Model,\n\tMain\Conf\Conf   as Conf;\n\nclass {MODEL-NAME} extends Model\n{\n\t/**\n\t* [\$_sModel description]\n\t* @var string\n\t*/\n\tprotected \$_sType = '{MODEL-TYPE}';\n\n\t/**\n\t* [\$_sPrefix description]\n\t* @var string\n\t*/\n\tprotected \$_sPrefix = '{MODEL-PREFIX}';\n\n\t/**\n\t* [\$_sRefererKey description]\n\t* @var string\n\t*/\n\tprotected \$_sRefererKey = '{MODEL-REFERER}';\n\n\t/**\n\t* [getStructure description]\n\t* @return [type] [description]\n\t*/\n\tpublic function getStructure(){\n\n\t\treturn array({MODEL-STRUCTURE}\t\t);\n\n\t}\n}",
			),
		'order' => array(
				"template"=>"<?php\n\nuse Core\Model\Order\Order as Order;\n\nclass {MODEL-NAME}Order extends Order{}",
			),

	);

	/**
	 * [$_aOutPath description]
	 * @var array
	 */
	private $_aOutPath = array(
		'model' => array('path'=>PATH_MOD,'name'=>'{MODEL-NAME}.php'),
		'order' => array('path'=>PATH_ORD,'name'=>'{MODEL-NAME}Order.php'),
	);


	/**
	 * [__construct description]
	 * @param [type] $sBase [description]
	 */
	public function __construct( $sBase){
		$this->_sBase = $sBase;
		$this->_oBase = new Base();
		$this->_oBase->buildSchema( $sBase);
	}

	/**
	 * [_setError description]
	 * @param [type] $sType [description]
	 * @param [type] $sMsg  [description]
	 */
	private function _setError( $sType, $sMsg){
		$this->_aError[$sType][] = $sMsg;
		return $this;
	}

	/**
	 * [_getNameFile description]
	 * @param  [type] $oTable [description]
	 * @param  [type] $sFile  [description]
	 * @return [type]         [description]
	 */
	private function _getNameFile($oTable, $sFile){
		$aInfo = $oTable->getReplace();
		$sName = $oTable->getName();

		$sNameFile = str_replace( array_keys( $aInfo), array_values( $aInfo), $this->_aOutPath[$sFile]['name']);
		$sPathFile = $this->_aOutPath[$sFile]['path'].DS.$sNameFile;

		return $sPathFile;
	}

	/**
	 * [_getContent description]
	 * @param  [type]  $oTable [description]
	 * @param  boolean $bPath  [description]
	 * @return [type]          [description]
	 */
	private function _getContent( $oTable, $sTemplate = '', $bPath  = false){

		if( !$bPath){
			$aInfo = $oTable->getReplace();
			return str_replace( array_keys( $aInfo), array_values( $aInfo), $sTemplate);
		}else{
			ob_start();
				include( $sTemplate);
				$sNewTemplate = ob_get_contents();
			ob_end_clean();
			return $sNewTemplate;
		}

	}

	/**
	 * [generate description]
	 * @return [type] [description]
	 */
	private function _generateFile( $sFile){
		$aTable    = $this->getTable();

		if( array_key_exists('src', $this->_aTemplate[$sFile])){
			$sTemplate = $this->_aTemplate[$sFile]['src'];
			$bPath = true;
		}else{
			$sTemplate = $this->_aTemplate[$sFile]['template'];
			$bPath = false;
		}

		foreach( $aTable as $oTable){
			$sPathFile = $this->_getNameFile($oTable, $sFile);

			if( !file_exists( $sPathFile)){
				$sNewTemplate = $this->_getContent( $oTable, $sTemplate, $bPath);

				file_put_contents($sPathFile, $sNewTemplate);
			}
		}


		return true;
	}

	/**
	 * [getListTable description]
	 * @return [type] [description]
	 */
	public function getListTable(){
		return $this->_oBase->getListTable();
	}

	/**
	 * [getTable description]
	 * @return [type] [description]
	 */
	public function getTable(){
		return $this->_oBase->getTable();
	}

	/**
	 * [setOutPath description]
	 * @param [type] $aArray [description]
	 */
	public function setOutPath( $aArray){
		$this->_aOutPath = $aArray;
		return $this;
	}

	/**
	 * [setReplace description]
	 * @param [type] $aArray [description]
	 */
	public function setTemplate( $aArray){
		$this->_aTemplate = $aArray;
		return $this;
	}

	/**
	 * [getError description]
	 * @return [type] [description]
	 */
	public function getError(){
		return $this->_aError;
	}

	/**
	 * [generate description]
	 * @return [type] [description]
	 */
	public function generate(){
		foreach( $this->_aTemplate as $sTypeFile => $sValue){
			$this->_generateFile( $sTypeFile);
		}

		return true;
	}

	/**
	 * [__get description]
	 * @param  [type] $sChamp [description]
	 * @return [type]         [description]
	 */
	public function __get( $sChamp){
		$sChamp = strtolower( $sChamp);
		return $this->_oBase->$sChamp;
	}

}
