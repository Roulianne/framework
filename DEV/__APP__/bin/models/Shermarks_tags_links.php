<?php 

use Main\Model\Model as Model,
	Main\Conf\Conf as Conf;

class Shermarks_tags_links extends Model
{
	/**
	* [$_sModel description]
	* @var string
	*/
	protected $_sType = 'shermarks_tags_links';

	/**
	* [$_sPrefix description]
	* @var string
	*/
	protected $_sPrefix = 'link_';

	/**
	* [$_sRefererKey description]
	* @var string
	*/
	protected $_sRefererKey = 'link_id';

	/**
	* [getStructure description]
	* @return [type] [description]
	*/
	public function getStructure(){

		return array(
				'id' => array(
					'type' => 'int',
					'value' => $this->get('link_id'),
				),

				'tag_id' => array(
					'type' => 'int',
					'value' => $this->get('link_tag_id'),
				),

				'tag_shermark' => array(
					'type' => 'int',
					'value' => $this->get('link_tag_shermark'),
				),

		);

	}
}