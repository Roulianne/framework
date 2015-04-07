<?php
namespace Core\Dao\Php;

use Core\Dao\Php\Accessible as Accessible,
    \DOMDocument,
    \DOMElement,
    \DOMXPath,
    \Exception;

class XmlDao extends DOMDocument implements Accessible
{
    private $_oData  = NULL;
    
    private $_aWhere = array();
    
    public  $TYPE    = 'XML';


    /**
     * [__construct description]
     * @param [type] $sFile
     */
    public function __construct($aParams)
    {
        if ( is_array( $aParams) and array_key_exists('file', $aParams)) {
            parent::__construct();
            $this->preserveWhiteSpace = false;
            $this->validateOnParse = true;
            $this->load( $aParams['file']);
            $this->_oXpath    = new DOMXPath( $this);
        } else {
            throw new Exception("Failed to open the file");
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
            $mValue = (array_key_exists( $sKey, $this->_aWhere))? $this->_aWhere[$sKey] : NULL;
       }

       return $mValue;
    }

    /**
     * [objectToArray description]
     * @param  [type] $oData
     * @param  array  $aData
     * @return [type]
     */
    public function objectToArray($oData, $aData = array())
    {
        foreach ((array) $oData as $mKey => $mValue) {
            $sProperty = strtolower( trim( $mKey));
            $aData[$sProperty] = ( is_object( $mValue) || is_array( $mValue)) ? $this->objectToArray( $mValue) : $mValue;
        }

        return $aData;
    }

    /**
     * [getFormatValues description]
     * @param  [type] $aData
     * @return [type]
     */
    public function getFormatValues($oDOMelement)
    {
        $oNewDoc     = new DOMDocument();
        $oDOMcloned  = $oDOMelement->cloneNode( TRUE);

        $oNewDoc->appendChild($oNewDoc->importNode( $oDOMcloned, TRUE));
        $sDomElement =  $oNewDoc->saveHTML();

        $oXmlObject = simplexml_load_string( $sDomElement, 'SimpleXMLElement', LIBXML_NOCDATA);

        return $this->objectToArray( $oXmlObject);
    }

    /**
     * [read description]
     * @param  [type] $sTable
     * @param  [type] $aWhere
     * @return [type]
     */
    public function read( $aSelect = array())
    {
        $sQuery = $this->_getCondition( 'type');
        $aWhere = $this->_getCondition( 'where');

        $aResult = array();

        if ($aWhere != NULL) {
            foreach ($aWhere as $sKey => $sValue) {
                $sQuery .= '[@'.$sKey.'=\''.$sValue.'\']';
            }
        }

        $oView = $this->_oXpath->query( $sQuery);

        for ($i=0 ; $i<$oView->length; $i++) {
            $aResult[] = $this->getFormatValues( $oView->item( $i));
        }

        return  $aResult;
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
