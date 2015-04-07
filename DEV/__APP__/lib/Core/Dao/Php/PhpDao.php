<?php
namespace Core\Dao\Php;

use Core\Dao\Php\Accessible as Accessible,
    \Exception;

class PhpDao implements Accessible
{
    protected $_aDataFile = array();

    protected $_aWhere    = array();

    protected $_sFolder   = '';

    public  $TYPE         = 'PHP';


    /**
     * [__construct description]
     * @param [type] $sFile
     */
   public function __construct($aParams)
   {
        if ( is_array( $aParams) and array_key_exists('folder', $aParams)) {
            if( is_dir( $aParams['folder'])){
                $sFolder =  $aParams['folder'];
            }
            if( is_file( $aParams['folder'])){
                $aInfo   = pathinfo( $aParams['folder']);
                $sFolder =  $aInfo['dirname'];
                $sType   =  $aInfo['filename'];

                $this->setCondition(
                    array('type'=>$sType)
                );
            }

            $this->_sFolder = $sFolder;
        } else {
            throw new Exception("Failed to open the folder");
        }
    }

    /**
     * [_loadFileData description]
     * @return [type] [description]
     */
    protected function _loadFileData(){
        $sFolder = rtrim( $this->_sFolder, '/');
        $this->_aDataFile = $this->_loadPhpArray( $sFolder.'/'.$this->_getCondition( 'type').'.php');
    }

    /**
     * [_saveData description]
     * @return [type] [description]
     */
    protected function _saveData(){
        $sFolder = rtrim( $this->_sFolder, '/');
        $sData   = $this->_convertArrayToString( $this->_aDataFile);
        file_put_contents( $sFolder.'/'.$this->_getCondition( 'type').'.php', $sData);
    }

    /**
     * [_getCondition description]
     * @param  [type] $sKey [description]
     * @return [type] [description]
     */
    protected function _getCondition($sKey = NULL)
    {
       $mValue = NULL;

       if ($sKey == NULL) {
            $mValue = $this->_aWhere;
       } else {
            $mValue = (array_key_exists( $sKey, $this->_aWhere))? $this->_aWhere[$sKey] : array();
       }

       return $mValue;
    }
    /**
     * [_convertArrayToString description]
     * @param  [type] $aData [description]
     * @return [type]        [description]
     */
    private function _convertArrayToString( $aData){


        $sResult = "<?php \n\n return ".preg_replace("/[0-9]+ \=\>/i", '', var_export( $aData, true)).";\n\n ?>";
        $sResult = str_replace('array (', 'array(', $sResult);

        return $sResult;
    }

    /**
     * [_searchOnArray description]
     * @param  [type] $array  [description]
     * @param  [type] $aWhere [description]
     * @return [type] [description]
     */
    private function _searchKeyOnArray($aData, $aWhere = array())
    {
        $iResult = false;

        $aWhere  = array_change_key_case( $aWhere, CASE_LOWER);

        foreach ($aData as $iIndex => $aInfo) {
            $bValid = true;
            foreach ((array) $aWhere as $sKey => $sValue) {

                if ( !array_key_exists( $sKey, $aInfo)|| $aInfo[$sKey] != $sValue) {
                    $bValid = false;
                    continue 2;
                }
            }

            if($bValid)
                $iResult = $iIndex;
        }

        return $iResult;
    }

    /**
     * [_searchOnArray description]
     * @param  [type] $array  [description]
     * @param  [type] $aWhere [description]
     * @return [type] [description]
     */
    private function _searchOnArray($aData, $aWhere = array())
    {
        $aResult = array();

        $aWhere  = array_change_key_case( $aWhere, CASE_LOWER);

        foreach ($aData as $aInfo) {
            $bValid = true;
            foreach ((array) $aWhere as $sKey => $sValue) {

                if ( !array_key_exists( $sKey, $aInfo)|| $aInfo[$sKey] != $sValue) {
                    $bValid = false;
                    continue 2;
                }
            }

            if($bValid)
                $aResult[] = $aInfo;
        }
        return $aResult;
    }

    /**
     * [getFormatValues description]
     * @param  [type] $aData
     * @return [type]
     */
    private function _loadPhpArray($sFile)
    {
        return include($sFile);
    }

    /**
     * [read description]
     * @param  [type] $sTable
     * @param  [type] $aWhere
     * @return [type]
     */
    public function read( $aSelect = array())
    {
        $aWhere  = $this->_getCondition( 'where');
        $aResult = array();

        $this->_loadFileData();

        $aResult = $this->_searchOnArray( $this->_aDataFile, $aWhere);

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
        $this->_loadFileData();
        $this->_aDataFile[] = $aData;
        $this->_saveData();
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
        $aWhere  = $this->_getCondition( 'where');
        $this->_loadFileData();
        $iKey    = $this->_searchKeyOnArray( $this->_aDataFile, $aWhere);

        if( $iKey !== false){
            $this->_aDataFile[$iKey] = $aData;
            $this->_saveData();
        }
    }

    /**
     * [delete description]
     * @param  [type] $sTable
     * @param  array  $aWhere
     * @return [type]
     */
    public function delete()
    {
        $aWhere  = $this->_getCondition( 'where');
        $this->_loadFileData();
        $iKey    = $this->_searchKeyOnArray( $this->_aDataFile, $aWhere);

        if( $iKey !== false){
            unset( $this->_aDataFile[$iKey]);
            $this->_saveData();
        }
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
    public function setCondition(array $aWhere = NULL)
    {
        if (is_array( $aWhere)) {
            if( array_key_exists('type', $this->_aWhere) and !array_key_exists('type', $aWhere) ){
                $aWhere['type'] = $this->_aWhere['type'];
            }
            $this->_aWhere = $aWhere;
        }

        return $this;
    }

    /**
     * [getData description]
     * @return [type] [description]
     */
    public function getData()
    {
        return $this->_aDataFile;
    }

    /**
     * [mergeData description]
     * @param  [type] $aOld [description]
     * @return [type] [description]
     */
    public function mergeData($aOld)
    {
        $this->_aDataFile = array_merge( $aOld, $this->_aDataFile);

        return $this;
    }
}
