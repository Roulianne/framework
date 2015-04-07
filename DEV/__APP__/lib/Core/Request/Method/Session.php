<?php

namespace Core\Request\Method;

class Session
{
    /**
     * [$_sType description]
     * @var string
     */
    protected $_sType = 'session';

    /**
     * [$_sKey description]
     * @var string
     */
    protected $_sKey  = 'pollux';

    /**
     * [$_bEmpty description]
     * @var boolean
     */
    protected $_bCanBeEmpty = false;

    /**
     * [__construct description]
     */
    public function __construct() {}

    /**
     * [get description]
     * @param  string $sKey [description]
     * @return [type] [description]
     */
    public function get($sKey = '')
    {
        $sKeyNew     = $this->_sKey.'_'.$sKey;
        if (array_key_exists($sKeyNew,  $_SESSION)) {
            return $_SESSION[$sKeyNew];
        } else {
            return null;
        }
    }

    /**
     * [set description]
     * @param string $sKey   [description]
     * @param [type] $mValue [description]
     */
    public function set($sKey = '', $mValue = NULL)
    {
        if ($sKey != '' and !is_null( $mValue)) {
            $sKeyNew            = $this->_sKey.'_'.$sKey;
            if( $mValue != '' || $_bCanBeEmpty){
                $_SESSION[$sKeyNew] = $mValue;
            }
        }

        return $this;
    }

    /**
     * [delete description]
     * @param  string $sKey [description]
     * @return [type] [description]
     */
    public function delete($sKey = '')
    {
        $sKeyNew     = $this->_sKey.'_'.$sKey;
        if (array_key_exists($sKeyNew,  $_SESSION)) {
            $_SESSION[$sKeyNew] = NULL;
            unset( $_SESSION[$sKeyNew]);
        }

        return $this;
    }

    /**
     * [getData description]
     * @return [type] [description]
     */
    public function getData()
    {
        return $_SESSION;
    }

}
