<?php
namespace Core\Loader\Php;

use Core\Loader\Loader as Loader;

class Script extends Loader
{
    /**
     * [_load description]
     * @param  [type] $sUri [description]
     * @return [type] [description]
     */
    protected function _load($sUri)
    {
        require $sUri;
    }
}
