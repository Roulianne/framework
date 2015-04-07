<?php

namespace Core\Loader;

use \Exception as Exception;

abstract class Loader
{
    protected $_aOptions = array(
            'pattern'       => NULL,
            'parameters'    => array( ),
    );

    /**
     * [_computeUris description]
     * @param  [type] $sResourceId [description]
     * @return [type] [description]
     */
    protected function _computeUris($sResourceId)
    {
        $aUris = array();

        if ( is_array( $this->_aOptions['pattern'])) {
            foreach ($this->_aOptions['pattern'] as $sPattern) {
                $aUris = array_merge( $aUris, $this->_computeUrisPattern( $sResourceId, $sPattern));
            }
        } else {
           $aUris = $this->_computeUrisPattern( $sResourceId, $this->_aOptions['pattern']);
        }

        return $aUris;
    }

    /**
     * [_computeUrisPattern description]
     * @param  [type] $sResourceId [description]
     * @param  [type] $sPattern    [description]
     * @return [type] [description]
     */
    protected function _computeUrisPattern($sResourceId, $sPattern) {//
        $aUris          = array();
        $aParameters    = $this->_aOptions['parameters'];

        if ( is_string( $sResourceId) && is_string( $sPattern)) {
            $aUris          = array( str_replace( '[:resourceId:]', $sResourceId, $sPattern));
        }

        foreach ($aParameters as $sParameter => $aValues) {
            $aIncompletesUris = array( );

            while ( $sIncompleteUri = array_shift( $aUris)) {

                foreach ((array) $aValues as $sValue) {
                    $sValue = str_replace( "[:$sParameter:]", $sValue,  $sIncompleteUri);
                    $aIncompletesUris[] = $sValue;
                }
            }
            $aUris = $aIncompletesUris;
        }

        return $aUris;
    }

    /**
     * [setOptions description]
     * @param array $aOptions [description]
     */
    public function setOptions(array $aOptions)
    {
        if (! array_key_exists( 'pattern', $aOptions)) {
            throw new Exception;
        }

        $aDefaults = array();

        if ( is_array( $aOptions['pattern'])) {
            foreach ($aOptions['pattern'] as $sPattern) {
                preg_match_all( '`\[:(.+):]`U', $sPattern, $aMatches)
                    && $aDefaultsNew = array_fill_keys( $aMatches[1], '');
                $aDefaults = array_merge($aDefaults, $aDefaultsNew);

            }
        } else {
            preg_match_all( '`\[:(.+):]`U', $aOptions['pattern'], $aMatches)
                && $aDefaults = array_fill_keys( $aMatches[1], '');
        }

        if (! array_key_exists( 'resourceId', $aDefaults)) {
            throw new Exception;
        }

        $this->_aOptions = $aOptions AND $this->_aOptions['parameters'] += $aDefaults;

        return $this;
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

            is_readable( $sUri) && ($sFoundUri = $sUri),
            $sUri = next( $aUris)
        );

        return $sFoundUri;
    }

    /**
     * [load description]
     * @param  [type] $sResourceId [description]
     * @return [type] [description]
     */
    public function load($sResourceId)
    {
        $mResource = NULL;
        if (NULL !== ($sUri = $this->find( $sResourceId))) {
            $mResource = (NULL !== ($mResource = $this->_load( $sUri))) ? $mResource : TRUE;
        }

        return $mResource;
    }

    /**
     * [_load description]
     * @param  [type] $sUri [description]
     * @return [type] [description]
     */
    abstract protected function _load( $sUri);
}
