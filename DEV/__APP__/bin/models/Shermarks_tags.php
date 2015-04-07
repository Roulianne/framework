<?php 

use Main\Model\Model as Model,
	Main\Conf\Conf as Conf;

class Shermarks_tags extends Model
{
	/**
	* [$_sModel description]
	* @var string
	*/
	protected $_sType = 'shermarks_tags';

	/**
	* [$_sPrefix description]
	* @var string
	*/
	protected $_sPrefix = 'tag_';

	/**
	* [$_sRefererKey description]
	* @var string
	*/
	protected $_sRefererKey = 'tag_id';

	/**
	* [getStructure description]
	* @return [type] [description]
	*/
	public function getStructure(){

		return array(
				'id' => array(
					'type' => 'int',
					'value' => $this->get('tag_id'),
				),

				'referer' => array(
					'type' => 'varchar',
					'value' => $this->get('tag_referer'),
				),

				'title' => array(
					'type' => 'varchar',
					'value' => $this->get('tag_title'),
				),

				'date' => array(
					'type' => 'datetime',
					'value' => $this->get('tag_date'),
				),

		);

	}
}