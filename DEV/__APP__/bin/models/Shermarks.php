<?php

use Main\Model\Model as Model;

class Shermarks extends Model
{
    /**
     * [$_sModel description]
     * @var string
     */
    protected $_sType = 'shermarks';

    /**
     * [$_sReferer description]
     * @var string
     */
	protected $_sReferer = 'id';

    /**
     * [$_sPrefix description]
     * @var string
     */
    protected $_sPrefix  = 'shermark_';

    /**
     * [getStructure description]
     * @return [type] [description]
     */
    public function getStructure(){

       return array(
        'shermark_id'          => array( 'type'  =>'hidden',
                                         'filter'=> FILTER_SANITIZE_STRING,
                                         'value' =>$this->get('id')),

        'shermark_title'       => array( 'type'  =>'text',
                                         'filter'=> FILTER_SANITIZE_STRING,
                                         'value' =>$this->get('title')),

        'shermark_uri'         => array( 'type'  =>'text',
                                         'filter'=> FILTER_SANITIZE_STRING,
                                         'value' =>$this->get('uri')),

        'shermark_description' => array( 'type'  =>'textarea',
                                         'filter'=> FILTER_SANITIZE_STRING,
                                         'value' =>$this->get('description')),

        'shermark_image'       => array( 'type'  =>'file',
                                         'filter'=> FILTER_SANITIZE_STRING,
                                         'value' =>$this->get('image')),

        'shermark_date'        => array( 'type'  =>'date',
                                         'filter'=> FILTER_SANITIZE_STRING,
                                         'value' =>$this->get('date')),

        'shermark_user'        => array( 'type'  =>'select',
                                         'filter'=> FILTER_SANITIZE_STRING,
                                         'value' =>$this->get('user')),

        );

    }
}
