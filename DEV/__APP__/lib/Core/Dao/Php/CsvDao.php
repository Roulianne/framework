<?php
namespace Core\Dao\Php;

use Core\Dao\Php\PhpDao as PhpDao,
    Core\Dao\Php\Accessible as Accessible,
    \Exception;

/**
* This is the description for my class.
*
* @class CsvDao
* @constructor
*/
class CsvDao extends PhpDao implements Accessible
{

    public  $TYPE         = 'CSV';

    private $_sDelimiter  = "|";

    /**
     * [_loadFileData description]
     * @return [type] [description]
     */
    protected function _loadFileData(){
        $sFolder = rtrim( $this->_sFolder, '/');
        $this->_aDataFile = $this->_loadData( $sFolder.'/'.$this->_getCondition( 'type').'.csv', $this->_sDelimiter);
    }

    /**
     * @method _loadData description
     * @param  String $sFile      [description]
     * @param  String $sDelimiter [description]
     * @return Array [description]
     */
    private function _loadData($sFile, $sDelimiter)
    {
        $aAttribute  = array();
        $aDataResult = array();

        if (($handle = fopen( $sFile, "r")) !== FALSE) {

            while ( ( $aData = fgetcsv( $handle, 0, $sDelimiter)) !== FALSE) {
                if ( empty( $aAttribute)) {
                    $aAttribute = array_map( 'strtolower', $aData);
                } else {
                    $aDataResult[] = array_combine( $aAttribute, $aData);
                }
            }

            fclose($handle);
        }

        return $aDataResult;
    }

    /**
     * [_saveData description]
     * @return [type] [description]
     */
    protected function _saveData(){
        $sFolder = rtrim( $this->_sFolder, '/');
        $aData   = $this->_aDataFile;

        if( count( $aData)>0){
            $aFields = array_keys( $aData[0]);
            array_unshift($aData, $aFields);
        }

        $fp = fopen( $sFolder.'/'.$this->_getCondition( 'type').'.csv', 'w');

        foreach ($aData as $aInfo) {
            fputcsv($fp, $aInfo, $this->_sDelimiter);
        }

        fclose($fp);
    }

    /**
     * @method read description
     * @param  Array  $aData [description]
     * @return Array [description]
     */
    public function read( $aSelect = array())
    {
        return parent::read( $aSelect);
    }

    /**
     * @method create description
     * @param  array  $aData
     * @return null
     */
    public function create(array $aData)
    {
        parent::create( $aData);
    }

    /**
     * @method update description
     * @param  array  $aData
     * @return null
     */
    public function update(array $aData)
    {
       parent::update( $aData);
    }

    /**
     * @method delete description
     * @return null
     */
    public function delete()
    {
        parent::delete();
    }

    /**
     * [getStructure description]
     * @param  [type] $oElment [description]
     * @return [type]          [description]
     */
    public function options( $oElement){
         return $oElement->getStructure();
    }

}
