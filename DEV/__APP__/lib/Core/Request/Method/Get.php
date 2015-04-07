<?php

namespace Core\Request\Method;

use Core\Request\Method\Method       as Method,
    Core\Dispenser\Dispenser as Dispenser;

class Get extends Method
{
    /**
     * [$_sType description]
     * @var string
     */
    protected $_sType = 'get';

    /**
     * [$_bEmpty description]
     * @var boolean
     */
    protected $_bCanBeEmpty = false;


}
