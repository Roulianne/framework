<?php
namespace Main\PathLoader;

use Core\Loader\Php\Script as Script;

class PathLoader extends Script
{
    /**
     * [_load description]
     * @param  [type] $sUri [description]
     * @return [type] [description]
     */
    protected function _load($sPath)
    {
        return $sPath;
    }

    /**
     * [_is_exist description]
     * @param  [type]  $sUri [description]
     * @return boolean [description]
     */
    private function _is_exist($sUri)
    {
        if ( filter_var( $sUri, FILTER_VALIDATE_URL)) {
            return @fopen( $sUri, 'r');
        } else {
            return is_readable( $sUri);
        }
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

            $this->_is_exist( $sUri) && ($sFoundUri = $sUri),
            $sUri = next( $aUris)
        );

        return $sFoundUri;
    }
}
