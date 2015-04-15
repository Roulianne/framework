<?php

use Main\Model\Model as Model,
	Main\Conf\Conf   as Conf;

class Article extends Model
{
	/**
	* [$_sModel description]
	* @var string
	*/
	protected $_sType = 'article';

	/**
	* [$_sPrefix description]
	* @var string
	*/
	protected $_sPrefix = '';

	/**
	* [$_sRefererKey description]
	* @var string
	*/
	protected $_sRefererKey = 'id';

	/**
     * [_getConf specification du DAO utilisé]
     * @return [type] [description]
     */
    protected function _getConf()
    {
        return Conf::get('model_pdo');// configuration dans le ini => [model_pdo]
    }

	/**
	* [getStructure description]
	* @return [type] [description]
	*/
	public function getStructure(){

		return array(
				'id_article' => array(
					'type'  => 'int',
					'value' => $this->get('id_article'),
				),

				'nom' => array(
					'type'  => 'varchar',
					'value' => $this->get('nom'),
				),

				'description' => array(
					'type'  => 'text',
					'value' => $this->get('description'),
				),

				'date' => array(
					'type'  => 'datetime',
					'value' => $this->get('date'),
				),

				'actif' => array(
					'type'  => 'tinyint',
					'value' => $this->get('actif'),
				),

		);

	}

	/** [reveal description] */
	public function reveal( $aData = array()){

		$aData = $this->getData();
		$aData['resume'] = "lire la suite cliquez";
		return parent::reveal( $aData);
	}
}