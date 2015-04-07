<?php
namespace Image\Image;

use Image\ResourceImage\ResourceImage as ResourceImage;

class Image{
    public function __construct(){}


    private function _convertToObject( $rImage){

         if (!imageistruecolor( $rImage)) {
            list( $iWidth, $iHeight) = array( imagesx( $rImage), imagesy( $rImage));

            $truecolor   = imagecreatetruecolor( $iWidth, $iHeight);
            $transparent = imagecolorallocatealpha($truecolor, 255, 255, 255, 127);

            imagefill($truecolor, 0, 0, $transparent);
            imagecolortransparent($truecolor, $transparent);

            imagecopymerge($truecolor, $rImage, 0, 0, 0, 0, $iWidth, $iHeight, 100);

            imagedestroy($rImage);
            $rImage = $truecolor;
        }

        imagealphablending($rImage, false);
        imagesavealpha($rImage, true);

        if (function_exists('imageantialias')) {
            imageantialias($rImage, true);
        }

        return new ResourceImage( $rImage);
    }

    public function open( $sPath){
        $flux     = @fopen($sPath, 'r');
        $string   = stream_get_contents( $flux);
        $resource = @imagecreatefromstring($string);

        return $this->_convertToObject( $resource);
    }

    public function create( $oBox, $oColor = null){

        $rImageVide = imagecreatetruecolor( $oBox->getWidth(), $oBox->getHeight());

        $oColor = ( !is_null($oColor)) ? $oColor : new Color('fff');

        $index = imagecolorallocatealpha( $rImageVide, $oColor->getRed(), $oColor->getGreen(), $oColor->getBlue(), round(127 * $oColor->getAlpha() / 100));

        if ($oColor->getAlpha() >= 95) {
            imagecolortransparent( $rImageVide,  $index);
        }

        imagefill( $rImageVide, 0, 0, $index);

        return $this->_convertToObject( $rImageVide);

    }


}