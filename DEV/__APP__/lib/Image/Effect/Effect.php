<?php
namespace Image\Effect;

final class Effect{

	/** @var ResourceImage [description] */
	private $_oResourceImage;

	/**
	 * [__construct description]
	 * @param [type] $rImage [description]
	 */
	public function __construct( $oResourceImage){
        $this->_oResourceImage = $oResourceImage;
	}

	/**
	 * [gamma description]
	 * @param  [type] $iCorrection [description]
	 * @return [type]              [description]
	 */
    public function gamma( $iCorrection){
        imagegammacorrect($this->_oResourceImage->getResource(), 1.0, $iCorrection);
        return $this;
    }

    /**
     * [negative description]
     * @return [type] [description]
     */
    public function negative(){
        imagefilter( $this->_oResourceImage->getResource(), IMG_FILTER_NEGATE);
        return $this;
    }

    /**
     * [grayscale description]
     * @return [type] [description]
     */
    public function grayscale(){
    	imagefilter( $this->_oResourceImage->getResource(), IMG_FILTER_GRAYSCALE);
        return $this;
    }

    /**
     * [colorize description]
     * @param  [type] $oColor [description]
     * @return [type]         [description]
     */
    public function colorize( $oColor){
        imagefilter( $this->_oResourceImage->getResource(), IMG_FILTER_COLORIZE, $oColor->getRed(), $oColor->getGreen(), $oColor->getBlue());
        return $this;
    }

    /**
     * [sharpen description]
     * @return [type] [description]
     */
    public function sharpen(){
        $aSharpenMatrix = array( 	array( -1, -1, -1),
        							array( -1, 16, -1),
        							array( -1, -1, -1),
        						);

        $iDivisor = array_sum(array_map('array_sum', $aSharpenMatrix));

        imageconvolution( $this->_oResourceImage->getResource(), $aSharpenMatrix, $iDivisor, 0);

        return $this;
    }

    /**
     * [gaussian description]
     * @return [type] [description]
     */
    public function gaussian( $iPassage= 1){
    	$aGaussianMatrix = array(	array( 1, 2, 1),
    								array( 2, 4, 2),
    								array( 1, 2, 1),
    							);

        $iDivisor = array_sum(array_map('array_sum', $aGaussianMatrix));

        for($i=0;$i<=$iPassage;$i++){
		  imageconvolution( $this->_oResourceImage->getResource(), $aGaussianMatrix, $iDivisor, 0);
        }
		return $this;
    }

    /**
     * [apply description]
     * @return [type] [description]
     */
    public function stopEffect(){
        return $this->_oResourceImage;
    }
}