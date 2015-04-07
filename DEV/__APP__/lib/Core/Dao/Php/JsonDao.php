<?php
namespace Core\Dao\Php;

use Core\Dao\Php\PhpDao as PhpDao,
    Core\Dao\Php\Accessible as Accessible,
    \Exception;

class JsonDao extends PhpDao implements Accessible
{

    public  $TYPE         = 'JSON';

    /**
     * [_loadFileData description]
     * @return [type] [description]
     */
    protected function _loadFileData(){
        $sFolder = rtrim( $this->_sFolder, '/');
        $this->_aDataFile = json_decode( file_get_contents( $sFolder.'/'.$this->_getCondition( 'type').'.json'), true);
    }

    /**
     * [_saveData description]
     * @return [type] [description]
     */
    protected function _saveData(){
        $sFolder = rtrim( $this->_sFolder, '/');
        $sData   = json_encode( $this->_aDataFile);
        file_put_contents( $sFolder.'/'.$this->_getCondition( 'type').'.json', $sData);
    }

    /**
    * [read description]
    * @param  array  $aData [description]
    * @return [type]        [description]
    */
    public function read(  $aSelect = array())
    {
        return parent::read(  $aSelect);
    }

    /**
     * [create description]
     * @param  [type] $sTable
     * @param  array  $aData
     * @return [type]
     */
    public function create(array $aData){
        parent::create( $aData);
    }

    /**
     * [update description]
     * @param  [type] $sTable
     * @param  array  $aData
     * @param  array  $aWhere
     * @return [type]
     */
    public function update(array $aData){
        parent::update( $aData);
    }

    /**
     * [delete description]
     * @param  [type] $sTable
     * @param  array  $aWhere
     * @return [type]
     */
    public function delete(){
        parent::delete();
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
}
