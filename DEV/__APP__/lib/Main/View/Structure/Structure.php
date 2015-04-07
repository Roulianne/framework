<?php

namespace Main\View\Structure;

use Core\Dao\Dao as Dao;

class Structure
{
    private $_oDao = null;
    private $_aStructureData = array();

    /**
     * [__construct description]
     * @param [type] $sPath [description]
     */
    public function __construct($sPath, $sName = NULL)
    {
        $sExt = pathinfo( $sPath, PATHINFO_EXTENSION);

        $aConf = array();
        $aConf[$sExt]['folder'] = $sPath;

        $this->_oDao = Dao::getInstance( $aConf);
        $this->_aStructureData = ( is_null($sName))? $this->_oDao->read() : $this->_buildData( $sName);

    }

    /**
     * [getTemplate description]
     * @return [type] [description]
     */
    public function getData($sKey = NULL)
    {
        return ( is_null( $sKey) ||
                ( is_array($this->_aStructureData) AND
                 !array_key_exists($sKey, $this->_aStructureData)
                 ))?
                    $this->_aStructureData :
                    $this->_aStructureData[$sKey];
    }

    /**
     * [_buildStruture description]
     * @param  [type] $sName [description]
     * @return [type] [description]
     */
    private function _buildData( $sName)
    {

        $aAllResult = $this->_oDao->setCondition( array( 'where' => array('name'=> $sName)))->read();
        $aData = current($aAllResult);
        $this->_computeInheritances( $aData);

        return $aData;
    }

    /**
     * [_recurse description]
     * @param  [type] $array [description]
     * @param  [type] $aData [description]
     * @return [type] [description]
     */
    private function _recurse($aExtend, $aData)
    {
      foreach ($aData as $key => $value) {

        if (is_int( $key)) {
            $aExtend[] = $value;
            continue;
        }

        // create new key in $array, if it is empty or not an array
        if ( !isset( $aExtend[$key]) || ( isset( $aExtend[$key]) && !is_array( $aExtend[$key]))) {
               $aExtend[$key] = array();
        }

        // overwrite the value in the base array
        if ( is_array($value)) {
              $value = $this->_recurse( $aExtend[$key], $value);
        }

        $aExtend[$key] = $value;

      }

      return $aExtend;
    }

    /**
     * [_computeInheritances description]
     * @param  [type] $aData [description]
     * @return [type] [description]
     */
    private function _computeInheritances( &$aData)
    {
        if ( is_array($aData) AND array_key_exists( 'extends', $aData)) {
            $aExtend = $aData['extends'];
            foreach ( (array) $aData['extends'] as $sExtend) {
                $aExtend = $this->_buildData( $sExtend);
                //unset( $aExtend['name']);
                $aData   = $this->_recurse( $aExtend, $aData);//array_replace_recursive( $aExtend, $aData);//
            }
            unset( $aData['extends']);
        }
    }
}
