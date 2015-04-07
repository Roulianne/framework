<?php

namespace Image\Box;

use Image\Box\Box     as Box,
    Image\Point\Point as Point;

final class Box {

	/** @var integer [description] */
    private $_iWidth  = 150;

    /** @var integer [description] */
    private $_iHeight = 150 ;

    /**
     * [__construct description]
     * @param integer $iWidth  [description]
     * @param integer $iHeight [description]
     */
    public function __construct( $iWidth = 150, $iHeight = 150)
    {
        if ( $iHeight >= 1 && $iWidth >= 1) {
	        $this->_iWidth  = (int) $iWidth;
	        $this->_iHeight = (int) $iHeight;
        }
        return false;
    }

    /**
     * [getWidth description]
     * @return [type] [description]
     */
    public function getWidth(){
    	return $this->_iWidth;
    }

    /**
     * [getHeight description]
     * @return [type] [description]
     */
    public function getHeight(){
    	return $this->_iHeight;
    }

    /**
     * [square description]
     * @return [type] [description]
     */
    public function square()
    {
        return $this->getWidth() *  $this->getHeight();
    }

    /**
     * [scale description]
     * @param  [type] $ratio [description]
     * @return [type]        [description]
     */
    public function scale($ratio)
    {
        return new Box(round( $ratio * $this->getWidth()), round( $ratio * $this->getHeight()));
    }

    /**
     * [contains description]
     * @param  [type] $oBox   [description]
     * @param  [type] $oPoint [description]
     * @return [type]         [description]
     */
    public function contains( $oBox, $oPoint)
    {
        $oPoint = $oPoint ? $oPoint : new Point(0, 0);

        return $oPoint->in($this) &&
            $this->_iWidth >= $oBox->getWidth() + $oPoint->getX() &&
            $this->_iHeight >= $oBox->getHeight() + $oPoint->getY();
    }

    /**
     * [giveWidth description]
     * @param  [type] $iWidth [description]
     * @return [type]         [description]
     */
    public function giveWidth( $iWidth)
    {
        return $this->scale( $iWidth / $this->getWidth());
    }

    /**
     * [giveheight description]
     * @param  [type] $iHeight [description]
     * @return [type]          [description]
     */
    public function giveheight( $iHeight)
    {
        return $this->scale($iHeight / $this->getHeight());
    }

}