<?php

namespace Main\Builder\Helper;

class Field{

	/***
			[TABLE_CATALOG]            => def
			[TABLE_SCHEMA]             => sherfi_fabrique
			[TABLE_NAME]               => bookmarks
			[COLUMN_NAME]              => bookmark_id
			[ORDINAL_POSITION]         => 1
			[COLUMN_DEFAULT]           =>
			[IS_NULLABLE]              => NO
			[DATA_TYPE]                => int
			[CHARACTER_MAXIMUM_LENGTH] =>
			[CHARACTER_OCTET_LENGTH]   =>
			[NUMERIC_PRECISION]        => 10
			[NUMERIC_SCALE]            => 0
			[CHARACTER_SET_NAME]       =>
			[COLLATION_NAME]           =>
			[COLUMN_TYPE]              => int(6) unsigned
			[COLUMN_KEY]               => PRI
			[EXTRA]                    => auto_increment
			[PRIVILEGES]               => select,insert,update,references
			[COLUMN_COMMENT]           =>
	 ***/

	private $_aInfo = array();


	function __construct( $aInfo = array()){
		$this->_aInfo             = $aInfo;

		$this->_aInfo['NAME']     = $this->column_name;
		$this->_aInfo['REQUIRED'] = $this->isRequired();
	}

	/**
	 * [isPrimary description]
	 * @return boolean [description]
	 */
	public function isPrimary(){
		return ( $this->column_key == 'PRI');
	}

	/**
	 * [isPrimary description]
	 * @return boolean [description]
	 */
	public function isRequired(){
		return ( $this->is_nullable == 'NO');
	}

	/**
	 * [__get description]
	 * @param  [type] $sInfo [description]
	 * @return [type]        [description]
	 */
	public function __get( $sInfo){
		$sInfo = strtoupper( $sInfo);
		if( array_key_exists($sInfo, $this->_aInfo)){
			return $this->_aInfo[$sInfo];
		}
	}

	/**
	 * [getStrinfInfo description]
	 * @return [type] [description]
	 */
	public function getStringInfo(){
		$sResult = "array(\n";
		$sResult .= "\t\t\t\t\t'type'  => '{$this->data_type}',\n";
		$sResult .= "\t\t\t\t\t'value' => \$this->get('{$this->column_name}'),\n";
		$sResult .= "\t\t\t\t)";
		return $sResult;
	}

	/**
	 * [getInfo description]
	 * @return [type] [description]
	 */
	public function getInfo(){
		return $this->_aInfo;
	}

}
