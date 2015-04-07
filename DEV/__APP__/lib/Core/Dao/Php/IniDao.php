<?php
namespace Core\Dao\Php;

use Core\Dao\Php\Accessible as Accessible,
    \Exception;

class IniDao implements Accessible
{
    private $_aDataFile = array();

    private $_aWhere    = array();

    public  $TYPE         = 'INI';


    /**
     * [__construct description]
     * @param [type] $sFile
     */
    public function __construct($aParams)
    {
        if ( is_array( $aParams) and array_key_exists('file', $aParams)) {
            $aData = parse_ini_file( $aParams['file'], true);

            list( $aInheritances, $aParsed) = $this->_computeInheritance( $aData);

            $aParsed          = $this->_computeSequences( $aParsed);
            $this->_aDataFile = $this->_computeSections($aParsed, $aInheritances);
        } else {
            throw new Exception("Failed to open the file");
        }
    }

    /**
     * [_computeSections description]
     * @param  array  $aData         [description]
     * @param  array  $aInheritances [description]
     * @return [type] [description]
     */
    private function _computeSections(array $aData, array $aInheritances)
    {
        $aParsed = array_fill_keys( array_keys( $aInheritances), array( ));

        foreach ($aInheritances as $sSection => $aSectionInheritances) {

          foreach ($aSectionInheritances as $sSectionInheritances) {
            $aParsed[$sSection] = array_replace_recursive(
              $aParsed[$sSection],
              $aParsed[$sSectionInheritances]
            );
          }

          $aParsed[$sSection] = array_replace_recursive(
            $aParsed[$sSection],
            $aData[$sSection]
          );
        }

        // Retour de l'ensemble des sections
        return $aParsed;
      }

    /**
     * [_computeSequences description]
     * @param  array  $aData [description]
     * @return [type] [description]
     */
    private function _computeSequences(array $aData)
    {
    $aParsed = array();

    foreach ($aData as $sSection => $mValue) {

      list( $sSequence, $sSubSequence)
        = explode( '.', $sSection, 2) + array( 1 => NULL);

      (NULL === $sSubSequence) ?
        $aParsed[$sSequence] = $mValue:
        $aParsed[$sSequence][$sSubSequence] = $mValue;
    }

    foreach ($aParsed as $sSequence => $mValue) {
      if (is_array( $mValue)) {
        $aParsed[$sSequence] = $this->_computeSequences( $mValue);
      }
    }

    return $aParsed;
  }

    /**
     * [_computeInheritance description]
     * @param  [type] $sFile [description]
     * @return [type] [description]
     */
    private function _computeInheritance(array $aData)
    {
        $aParsed        = array();
        $aInheritances  = array();

        foreach ($aData as $sKey => $mValue) {

          list( $sSection, $sInheritances) =
            explode( ':', $sKey, 2) + array( 1 => NULL);

          $aInherited = explode( ',', $sInheritances)
            AND array_walk( $aInherited, function (&$sValue, $iKey) use (&$aInherited) {
              if ('' === ($sValue = trim( $sValue))) {
                unset( $aInherited[$iKey]);
              }
            });

          $aInheritances
            = array_diff_key( array_fill_keys( $aInherited, array( )), $aInheritances)
              + $aInheritances;

          $aInheritances[$sSection = trim( $sSection)]
            = array_reverse( $aInherited);

          $aParsed[$sSection] = $aData[$sKey];
        }

        return array( $aInheritances, $aParsed);
    }

    /**
     * [_parse_ini_file_extended description]
     * @param  [type] $sFile [description]
     * @return [type] [description]
     */
    private function _parse_ini_file_extended($sFile)
    {
        $aResult = array();

        foreach ($aIni as $sNameSpace => $aProp) {

                list( $sName, $sExtends) =
                    explode( ':', $sNameSpace, 2) + array( 1 => NULL);

                $sName    = trim( $sName);
                $sExtends = trim( $sExtends);

                if( !isset( $aResult[$sName])) $aResult[$sName] = array();

                if ( isset( $aIni[$sExtends])) {
                    foreach( $aIni[$sExtends] as $sProp => $sVal)
                        $aResult[$sName][$sProp] = $sVal;
                }
                foreach($aProp as $sProp => $sVal)
                    $aResult[$sName][$sProp] = $sVal;

        }

        return $aResult;
    }

    /**
     * [_getCondition description]
     * @param  [type] $sKey [description]
     * @return [type] [description]
     */
    private function _getCondition($sKey = NULL)
    {
       $mValue = NULL;

       if ($sKey == NULL) {
            $mValue = $this->_aWhere;
       } else {
            $mValue = (array_key_exists( $sKey, $this->_aWhere))? $this->_aWhere[$sKey] : NULL;
       }

       return $mValue;
    }

    /**
     * [read description]
     * @param  [type] $sTable
     * @param  [type] $aWhere
     * @return [type]
     */
    public function read(  $aSelect = array())
    {
        $sTable  = $this->_getCondition( 'type');

        if($sTable == '')
            $aResult = $this->_aDataFile;
        else
            $aResult = $this->_aDataFile[$sTable];

        return $aResult;
    }

    /**
     * [create description]
     * @param  [type] $sTable
     * @param  array  $aData
     * @return [type]
     */
    public function create(array $aData)
    {
        //
    }

    /**
     * [update description]
     * @param  [type] $sTable
     * @param  array  $aData
     * @param  array  $aWhere
     * @return [type]
     */
    public function update(array $aData)
    {
        //
    }

    /**
     * [delete description]
     * @param  [type] $sTable
     * @param  array  $aWhere
     * @return [type]
     */
    public function delete()
    {
        //
    }

    /**
     * [getStructure description]
     * @param  object $oElment [description]
     * @return [type]          [description]
     */
    public function options( $oElement)
    {
        return $oElement->getStructure();
    }

    /**
     * [setCondition description]
     * @param [type] $aWhere [description]
     */
    public function setCondition(array $aWhere = array())
    {
        if (is_array( $aWhere)) {
            $this->_aWhere = $aWhere;
        }

        return $this;
    }

}
