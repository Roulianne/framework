<?php

namespace Core\Request\Method;

use Core\Request\Method\Method  as Method,
    Core\Dispenser\Dispenser    as Dispenser;

class Put extends Method
{
    /**
     * [$_sType description]
     * @var string
     */
    protected $_sType = 'put';

    /**
     * [$_sFile description]
     * @var [type]
     */
    private   $_sFile = null;

    /**
     * [$_bEmpty description]
     * @var boolean
     */
    protected $_bCanBeEmpty = false;

    /**
     * [setFile description]
     * @param [type] $sFile [description]
     */
    public function setFile(){
        $this->_sFile = file_get_contents("php://input");
        return $this;
    }

     /**
     * [getFile description]
     * @param [type] $sFile [description]
     */
    public function getFile(){
        return $this->_sFile;
    }


}
