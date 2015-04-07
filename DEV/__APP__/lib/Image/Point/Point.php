<?php

namespace Image\Point;
use Image\Point\Point as Point;
final class Point{

	/** @var integer [description] */
	private $_iX = 0;

	/** @var integer [description] */
	private $_iY = 0;

	/**
	 * [_construct description]
	 * @param  integer $iX [description]
	 * @param  integer $iY [description]
	 * @return [type]      [description]
	 */
	public function  __construct( $iX = 0, $iY = 0){

		if( $iX >=0 && $iY >= 0){
			$this->_iX = (int)$iX;
			$this->_iY = (int)$iY;
		}
	}

	/**
	 * [in description]
	 * @param  [type] $oBox [description]
	 * @return [type]       [description]
	 */
    public function in( $oBox){
        return $this->getX() < $oBox->getWidth() && $this->getY() < $oBox->getHeight();
    }

    /**
     * [translate description]
     * @param  [type] $iX [description]
     * @param  [type] $iY [description]
     * @return [type]     [description]
     */
    public function translate( $iX = 0, $iY = 0){
        return new Point($this->getX() + $iX, $this->getY() + $iY);
    }

	/**
	 * [getX description]
	 * @return [type] [description]
	 */
	public function getX(){
		return $this->_iX;
	}

	/**
	 * [getY description]
	 * @return [type] [description]
	 */
	public function getY(){
		return $this->_iY;
	}

}