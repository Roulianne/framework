<?php
namespace Core\Dao\Php;

use Core\Dao\Php\Accessible as Accessible;
use \PDO;

class PdoDao extends PDO implements Accessible
{

    public  $TYPE         = 'PDO';

    private $_sStringCond = "";

    private $_aWhere      = array();

    private $_sOperateur  = '<>!=';

    /**
     * [__construct description]
     * @param [type] $sDsn
     * @param [type] $sUsername
     * @param [type] $sPassword
     * @param array  $aOptions
     */
    public function __construct($aParams)
    {
        $aParams += array('options'=>array());
        extract($aParams);
        parent::__construct( $dsn, $user, $password, $options );
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
            $mValue = ( array_key_exists( $sKey, $this->_aWhere))? $this->_aWhere[$sKey] : NULL;
       }

       return $mValue;
    }

    /**
     * [_buildOperateur description]
     * @param  [type] $sValue [description]
     * @return [type] [description]
     */
    private function _buildOperateur($sValue, $sOperateur = '')
    {
        $sFirstLetter = ( $sValue != '')? $sValue[0] : '=';

        if ( strstr( $this->_sOperateur, $sFirstLetter)) {
            $sOperateur .= $sFirstLetter;
            $sNewValue  = substr( $sValue, 1);
            $sOperateur = $this->_buildOperateur( $sNewValue, $sOperateur);
        }

        return $sOperateur;
    }

     /**
     * [get getFormatValues description]
     * @param  [type] $aWhere
     * @return [type]
     */
    public function getFormatValues( $aData, $sParams = array('WHERE ', ' AND '))
    {
        $sValue = NULL;

        if ( !is_null( $aData ) && !empty( $aData)) {
            $aStr   = array();

            foreach ($aData as $key => $value) {

                if ($sParams[0] =='WHERE ' || $sParams[0] =='SET ') {
                    if( $value != ''){
                        $sOperateur = $this->_buildOperateur( $value);
                        $sOperateur = ($sOperateur == '')? '=': $sOperateur;
                        $sNewValue  = str_replace( $sOperateur, '', $value);
                    }else{
                        $sNewValue = '';
                    }
                    $aStr[]     = "$key $sOperateur '$sNewValue'";
                } else {
                    $aStr[]     =( is_int($key))? "$value" : "$key $value";
                }

            }

            $sValue = $sParams[0] . implode( $sParams[1], $aStr);
        }

        return $sValue;
    }

    /**
     * [read description]
     * @param  [type] $sTable
     * @param  [type] $aWhere
     * @return [type]
     */
    public function read( $aSelect = array())
    {
        $sTable = $this->_getCondition( 'type');
        $aWhere = $this->_getCondition( 'where');
        $aOrder = $this->_getCondition( 'order');
        $aLimit = $this->_getCondition( 'limit');

        $aJoin  = $this->_getCondition( 'join');

        if ($this->_sStringCond == "") {

            $sWhere  = $this->getFormatValues( $aWhere );

            if( count( $aJoin) > 0){
                $aJoinTmp = array();
                foreach( $aJoin as $sKey => $sValue){
                    $aJoinTmp[]  = "{$sKey} = {$sValue}";
                }
                $sWhere .= ' AND '.implode( ' AND ', $aJoinTmp);
            }


            $sOrder  = $this->getFormatValues( $aOrder, array('ORDER BY ', ', ')  );
            $sLimit  = $this->getFormatValues( $aLimit, array('LIMIT ', ', ')  );

            $sOptionString = "{$sWhere} {$sOrder} {$sLimit}";
        } else {
            $sOptionString = $this->_sStringCond;
            $this->setCustomCondition();
        }

        $sSelect = ( count( $aSelect)>=1)? implode(', ',$aSelect) : '*';

        $req     = "SELECT {$sSelect} FROM {$sTable} {$sOptionString}";

        $sth     = $this->query($req);
        $aValues = array();

        if ($sth !== false) {
            while ( false !== ($infos = $sth->fetch(  PdoDao::FETCH_ASSOC)) ) {
                $aValues[] = $infos;
            }
        }

        return $aValues;
    }

    /**
     * [create description]
     * @param  [type] $sTable
     * @param  array  $aData
     * @return [type]
     */
    public function create(array $aData)
    {
        $sTable = $this->_getCondition( 'type');

        $aQuery   = array();

        foreach( $aData as $sKey => $sValue){
            if( $sValue == '' || empty( $sValue)){
                unset( $aData[$sKey]);
            }else{
                $aQuery[] = "{$sKey} = :{$sKey}";
            }
        }

        $sRequete = "INSERT INTO {$sTable} SET ".implode(", ", $aQuery );

        $sth     = $this->prepare( $sRequete);
        $result  = $sth->execute( $aData);

        if ($result !== false) {
            return $this->lastInsertId();
        } else {
            return false;
        }
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
        $sTable = $this->_getCondition( 'type');
        $aWhere = $this->_getCondition( 'where');
        $aLimit = $this->_getCondition( 'limit');

        $aRequete   = array();
        $aRequete[] = 'UPDATE';
        $aRequete[] = $sTable;
        $aRequete[] = $this->getFormatValues( $aData, array('SET ', ', ') );
        $aRequete[] = $this->getFormatValues( $aWhere );
        $aRequete[] = $this->getFormatValues( $aLimit, array('LIMIT ', ', ')  );

        $sRequete = implode( ' ', $aRequete);

        return $this->exec($sRequete);
    }

    /**
     * [delete description]
     * @param  [type] $sTable
     * @param  array  $aWhere
     * @return [type]
     */
    public function delete()
    {
        $sTable = $this->_getCondition( 'type');
        $aWhere = $this->_getCondition( 'where');
        $aLimit = $this->_getCondition( 'limit');

        $aRequete   = array();
        $aRequete[] = 'DELETE FROM';
        $aRequete[] = $sTable;
        $aRequete[] = $this->getFormatValues( $aWhere );
        $aRequete[] = $this->getFormatValues( $aLimit, array('LIMIT ', ', ')  );

        $sRequete = implode( ' ', $aRequete);

        return $this->exec($sRequete);
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

        if( array_key_exists('complement', $aWhere)){
            $this->_sStringCond = $aParams["complement"];
        }

        if (is_array( $aWhere)) {
            $this->_aWhere = $aWhere;
        }

        return $this;
    }
}
