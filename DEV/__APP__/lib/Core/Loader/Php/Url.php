<?php
namespace Core\Loader\Php;

use Core\Loader\Loader as Loader;

class Url extends Loader
{
    /**
     * [_load description]
     * @param  [type] $sUri [description]
     * @return [type] [description]
     */
    protected function _load($sUrl)
    {
        return $sUrl;
    }

    /**
     * [find description]
     * @param  [type] $sResourceId [description]
     * @return [type] [description]
     */
    public function find($sResourceId)
    {
        for (

            $sUri       = current( $aUris = $this->_computeUris( $sResourceId)),
            $sFoundUri  = NULL;

            (NULL === $sFoundUri) && (NULL !== key( $aUris));

            @fopen( $sUri, 'r') && ($sFoundUri = $sUri),
            $sUri = next( $aUris)
        );

        return $sFoundUri;
    }
}
