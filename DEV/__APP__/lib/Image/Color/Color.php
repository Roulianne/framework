<?php
namespace Image\Color;

Final class Color{

	/** @var integer [description] */
	private $_iRed   = 0;

	/** @var integer [description] */
	private $_iGreen = 0;

	/** @var integer [description] */
	private $_iBlue  = 0;

	/** @var integer [description] */
	private $_iAlpha = 0;


	/**
	 * [_construct description]
	 * @param  [type]  $mColor [description]
	 * @param  integer $iAlpha [description]
	 * @return [type]          [description]
	 */
	public function __construct( $mColor, $iAlpha = 0){
		$this->_setColor( $mColor);
		$this->_setAlpha( $iAlpha);
	}

	/**
	 * [_setColor description]
	 * @param [type] $mColor [description]
	 */
	private function _setColor( $mColor){

		$aColor = array( 0, 0, 0);

		//array : array(R,G,B);
		if( is_array( $mColor)){
			$iCount = count( $mColor);
			if( $iCount > 0 and $iCount <= 3){
				$aColor = $mColor;
			}
		}

		//int : 0xDCBEA5
		if (is_int($mColor)) {
            $aColor = array(
                255 & ($mColor >> 16),
                255 & ($mColor >> 8),
                255 & $mColor
            );
        }

        //string : fff , ffffff, #fff, #ffffff
        if (is_string($mColor)) {
            $mColor = ltrim($mColor, '#');

            if (strlen( $mColor) === 3 || strlen( $mColor) === 6){
	            if (strlen( $mColor) === 3){

	                $mColor = $mColor[0].$mColor[0].
	                          $mColor[1].$mColor[1].
	                          $mColor[2].$mColor[2];
	            }

	            $aColor = array_map( 'hexdec', str_split( $mColor, 2));
            }

        }

		list($this->_iRed, $this->_iGreen, $this->_iBlue) = array_values($aColor);
		return $this;

	}

	/**
	 * [_setAlpha description]
	 * @param [type] $iAlpha [description]
	 */
	private function _setAlpha( $iAlpha){
		$this->_iAlpha = (int) $iAlpha;
		return $this;
	}

	/**
	 * [dissolve description]
	 * @param  [type] $iAlpha [description]
	 * @return [type]         [description]
	 */
	public function dissolve( $iAlpha){
       return new Color( $this->getHex(), $iAlpha);
    }

    /**
     * [getHex description]
     * @return [type] [description]
     */
	public function getHex(){
		return sprintf( '#%02x%02x%02x', $this->getRed(), $this->getGreen(), $this->getBlue());
	}

	/**
	 * [getRed description]
	 * @return [type] [description]
	 */
	public function getRed(){
		return $this->_iRed;
	}

	/**
	 * [getGreen description]
	 * @return [type] [description]
	 */
	public function getGreen(){
		return $this->_iGreen;
	}

	/**
	 * [getBlue description]
	 * @return [type] [description]
	 */
	public function getBlue(){
		return $this->_iBlue;
	}

	/**
	 * [getAlpha description]
	 * @return [type] [description]
	 */
	public function getAlpha(){
		return $this->_iAlpha;
	}

	/**
     * Checks if the current color is opaque
     *
     * @return Boolean
     */
    public function isOpaque(){
        return 0 === $this->getAlpha();
    }
}