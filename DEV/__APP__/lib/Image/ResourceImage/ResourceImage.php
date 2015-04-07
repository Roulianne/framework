<?php

namespace Image\ResourceImage;

use Image\Box\Box     as Box,
    Image\Point\Point as Point,
    Image\Effect\Effect as Effect;

final class ResourceImage{

    /** @var resource [description] */
    private $_rImage  = NULL;

    /**
     * [__construct description]
     * @param [type] $rImage [description]
     */
    public function __construct( $rImage){
        $this->_rImage = $rImage;
    }

    /**
     * [__destruct description]
     */
    public function __destruct(){
        if ( is_resource( $this->getResource())) {
            imagedestroy( $this->getResource());
        }
    }

    /**
     * [_updateRessource description]
     * @param  [type] $rNewImage [description]
     * @return [type]            [description]
     */
    private function _updateRessource( $rNewImage){
        imagedestroy( $this->_rImage);
        $this->_rImage = $rNewImage;
        return $this;
    }

    /**
     * [_createImage description]
     * @param  [type] $oBox [description]
     * @return [type]       [description]
     */
    private function _createImage( $oBox){

        $rImageVide = imagecreatetruecolor( $oBox->getWidth(), $oBox->getHeight());

        imagealphablending( $rImageVide, false);
        imagesavealpha( $rImageVide, true);

        if (function_exists('imageantialias')) {
            imageantialias( $rImageVide, true);
        }

        $rAlpha = imagecolorallocatealpha( $rImageVide, 255, 255, 255, 127);
        imagefill( $rImageVide, 0, 0, $rAlpha);
        imagecolortransparent( $rImageVide, $rAlpha);

        return $rImageVide;
    }

    /**
     * [_traitOutput description]
     * @param  [type] $sFormat  [description]
     * @param  [type] $aOptions [description]
     * @param  [type] $sPath    [description]
     * @return [type]           [description]
     */
    private function _traitOutput( $sFormat, $aOptions, $sPath){

        $sFormat = strtolower( $sFormat);

        $sfunctionSave = 'image'.$sFormat;
        $aArgs         = array(&$this->_rImage, $sPath);

        if (($sFormat === 'jpeg' || $sFormat === 'png') && isset($aOptions['quality'])) {
            if ($sFormat === 'png') {
                $aOptions['quality'] = round((100 - $aOptions['quality']) * 9 / 100);
            }
            $aArgs[] = $aOptions['quality'];
        }

        if ( $sFormat === 'png' && isset( $aOptions['filters'])) {
            $aArgs[] = $aOptions['filters'];
        }

        if ( ( $sFormat === 'wbmp' || $sFormat === 'xbm') &&
            isset( $aOptions['foreground'])) {
            $aArgs[] = $aOptions['foreground'];
        }

        call_user_func_array($sfunctionSave, $aArgs);
    }

    /**
     * [getMimeType description]
     * @param  [type] $format [description]
     * @return [type]         [description]
     */
    public function getMimeType($sFormat){

        $sFormat  = strtolower($sFormat);

        if ('jpg' === $sFormat || 'pjpeg' === $sFormat) {
            $sFormat = 'jpeg';
        }

        static $aMimeTypes = array(
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'png'  => 'image/png',
            'wbmp' => 'image/vnd.wap.wbmp',
            'xbm'  => 'image/xbm',
        );

        return $aMimeTypes[$sFormat];
    }

    /**
     * [getColorAt description]
     * @param  [type] $oPoint [description]
     * @return [type]         [description]
     */
    public function getColorAt( $oPoint){
        if ($oPoint->in($this->getBox())) {

            $index  = imagecolorat( $this->getResource(), $oPoint->getX(), $oPoint->getY());
            $aInfo  = imagecolorsforindex( $this->getResource(), $index);

            $iRed   = $aInfo['red'];
            $iGreen = $aInfo['green'];
            $iBlue  = $aInfo['blue'];

            $iAlpha = (int) round( $info['alpha'] / 127 * 100);

            $oColor = new Color(array( $iRed, $iGreen, $iBlue), $iAlpha);

            return $oColor;
        }

    }

    /**
     * [copy description]
     * @return [type] [description]
     */
    public function copy(){
        $oBox = $this->getBox();

        $rCopy = $this->_createImage( $oBox);

        imagecopy( $rCopy, $this->getResource(), 0, 0, 0, 0, $oBox->getWidth(), $oBox->getHeight());

        return new ResourceImage( $rCopy);
    }

    /**
     * [paste description]
     * @param  [type] $oResource [description]
     * @param  [type] $oPoint    [description]
     * @return [type]            [description]
     */
    public function paste( $oResource, $oPoint){

        $oBox = $oResource->getBox();

        if ($this->getBox()->contains( $oBox, $oPoint)) {

            imagealphablending( $this->getResource(), true);
            imagealphablending( $oResource->getResource(), true);

            imagecopy($this->getResource(), $oResource->getResource(), $oPoint->getX(), $oPoint->getY(),0, 0, $oBox->getWidth(), $oBox->getHeight());

            imagealphablending( $this->getResource(), false);
            imagealphablending( $oResource->getResource(), false);

        }

        return $this;
    }

    /**
     * [crop description]
     * @param  [type] $oBox   [description]
     * @param  [type] $oPoint [description]
     * @return [type]         [description]
     */
    public function crop( $oPoint, $oBox){

        if ($oPoint->in($this->getBox())) {

            $rDest = $this->_createImage( $oBox);

            imagecopy($rDest, $this->getResource(), 0, 0,$oPoint->getX(), $oPoint->getY(), $oBox->getWidth(), $oBox->getHeight());

            $this->_updateRessource( $rDest);

            return $this;
        }
    }

    /**
     * [resize description]
     * @param  [type] $oBox [description]
     * @return [type]       [description]
     */
    public function resize( $oBox){

        $rDest = $this->_createImage( $oBox);

        imagealphablending( $this->getResource(), true);
        imagealphablending( $rDest, true);

        imagecopyresampled( $rDest, $this->getResource(), 0, 0, 0, 0, $oBox->getWidth(), $oBox->getHeight(), $this->getBox()->getWidth(), $this->getBox()->getHeight());

        imagealphablending( $this->getResource(), false);
        imagealphablending( $rDest, false);

        $this->_updateRessource( $rDest);

        return $this;
    }

    /**
     * [save description]
     * @param  [type] $sPath    [description]
     * @param  array  $aOptions [description]
     * @return [type]           [description]
     */
    public function save( $sPath, $aOptions = array()){
        $sFormat = isset($aOptions['format']) ? $aOptions['format'] : pathinfo( $sPath, PATHINFO_EXTENSION);

        if($sFormat == 'jpg'){
            $sFormat = 'jpeg';
        }

        $this->_traitOutput($sFormat, $aOptions, $sPath);

        return $this;
    }

    /**
     * [thumbnail description]
     * @return [type] [description]
     */
    public function thumbnail( $oBox, $sMode = 'contain', $iMarge = null){ //cover || contain
        $iWidth  = $oBox->getWidth();
        $iHeight = $oBox->getHeight();

        $aRatios = array(
            $iWidth  / $this->getBox()->getWidth(),
            $iHeight / $this->getBox()->getHeight()
        );

        $oThumbnail = $this->copy();

        if( strtolower( $sMode) == 'cover'){
            $iRatio = max( $aRatios);
        }else{
            $iRatio = min( $aRatios);
        }

        $oThumbnailBox = $oThumbnail->getBox()->scale( $iRatio);

        $oThumbnail->resize( $oThumbnailBox);


        if ( strtolower( $sMode) == 'cover') {

            $iX = max( 0, round( ( $oThumbnailBox->getWidth()  - $iWidth ) / 2));
            $iY = max( 0, round( ( $oThumbnailBox->getHeight() - $iHeight) / 2));

            if( !is_null( $iMarge)){
                $iX = ( $iX == 0 )? 0 : $iMarge;
                $iY = ( $iY == 0 )? 0 : $iMarge;
            }

            $oThumbnail->crop( new Point( $iX, $iY), $oBox);
        }

        return $oThumbnail;
    }

    /**
     * [effect description]
     * @return [type] [description]
     */
    public function effect(){
        return new Effect( $this);
    }

    /**
     * [getResource description]
     * @return [type] [description]
     */
    public function getResource(){
        return $this->_rImage;
    }

    /**
     * [getSize description]
     * @return [type] [description]
     */
    public function getBox(){
        return new Box(imagesx($this->getResource()), imagesy($this->getResource()));
    }
}