<?php
namespace Core\Dao\Php;

use Core\Dao\Php\Accessible as Accessible;
use \PDO;

class MysqlDao implements Accessible
{

    public  $TYPE         = 'MYSQL';


    private $_sStringCond = "";

    private $_aWhere      = array();

    private $_sOperateur  = '<>!=';

    private static $_rLink       = null;

    /**
     * [__construct description]
     * @param [type] $sDsn
     * @param [type] $sUsername
     * @param [type] $sPassword
     * @param array  $aOptions
     */
    public function __construct($aParams)
    {
        extract($aParams);

        if(  is_null( self::$_rLink) ){
            self::$_rLink = mysql_connect( $host, $user, $password );
            mysql_select_db( $base,  self::$_rLink);
        }
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
        $sFirstLetter = $sValue[0];

        if ( strstr( $this->_sOperateur, $sFirstLetter)) {
            $sOperateur .= $sFirstLetter;
            $sNewValue   = substr( $sValue, 1);
            $sOperateur  = $this->_buildOperateur( $sNewValue, $sOperateur);
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
        if (! is_null( $aData ) ) {
            $aStr   = array();

            foreach ($aData as $key => $value) {

                if ($sParams[0] =='WHERE ' || $sParams[0] =='SET ') {
                    $sOperateur = $this->_buildOperateur($value);
                    $sOperateur = ($sOperateur == '')? '=': $sOperateur;
                    $sNewValue  = mysql_real_escape_string( str_replace( $sOperateur, '', $value));
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

        if ($this->_sStringCond == "") {

            $sWhere  = $this->getFormatValues( $aWhere );
            $sOrder  = $this->getFormatValues( $aOrder, array('ORDER BY ', ', ')  );
            $sLimit  = $this->getFormatValues( $aLimit, array('LIMIT ', ', ')  );

            $sOptionString = "{$sWhere} {$sOrder} {$sLimit}";
        } else {
            $sOptionString = $this->_sStringCond;
            $this->setCustomCondition();
        }

        $sSelect = ( count( $aSelect)>=1)? implode(', ',$aSelect) : '*';

        $req     = "SELECT {$sSelect} FROM {$sTable} {$sOptionString}";

        $sth     = mysql_query($req);
        $aValues = array();

        if (mysql_num_rows($sth) != 0) {
            while ( $infos = mysql_fetch_assoc($sth)) {
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

        $sKeys    = implode(", ", array_keys( $aData ) );
        $sValues  = implode("', '", array_values( $aData ) );
        $sRequete = "INSERT INTO $sTable ( $sKeys ) VALUES ( '$sValues' )";

        return mysql_query($sRequete);
        /*
        if ($sth !== false) {
            return $sth;
        } else {
            return false;
        }
        */
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

        $sValues  = $this->getFormatValues( $aData, array('SET ', ', ') );
        $sWhere   = $this->getFormatValues( $aWhere );
        $sLimit   = $this->getFormatValues( $aLimit, array('LIMIT ', ', ')  );
        $sRequete = "UPDATE {$sTable} {$sValues} {$sWhere} {$sLimit}";

        return mysql_query($sRequete);
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

        $sWhere   = $this->getFormatValues( $aWhere );
        $sLimit   = $this->getFormatValues( $aLimit, array('LIMIT ', ', ')  );
        $sRequete = "DELETE FROM {$sTable} {$sWhere} {$sLimit}";

        return mysql_query($sRequete);
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
