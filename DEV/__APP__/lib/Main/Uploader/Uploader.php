<?php
namespace Main\Uploader;

class Uploader{

    /**
     * [$_listFile description]
     * @var array
     */
    protected static $_aFiles = array();

    /**
     * [$sFolder description]
     * @var string
     */
    public static $_sFolder = './ressources/';

    /**
     * [$_sDestnation description]
     * @var string
     */
    private static $_aDestination = array();

    /**
     * [$_aFilters description]
     * @var array
     */
    private static $_aFilters      = array();

    /**
     * [prepareFiles description]
     * @return [type] [description]
     */
    private static function _prepareFiles(){
    	self::$_aFiles = array();

        foreach ($_FILES as $sVarName => $aContent) {
            if( $aContent['name'] != ''){
                self::$_aFiles[$sVarName]              = $aContent;

                self::$_aFiles[$sVarName]['validated'] = false;
                self::$_aFiles[$sVarName]['received']  = false;
                self::$_aFiles[$sVarName]['filtered']  = false;

                self::$_aFiles[$sVarName]['transfert'] = 'file';

                $mimetype = self::_detectMimeType(self::$_aFiles[$sVarName]);
                self::$_aFiles[$sVarName]['type'] = $mimetype;

                $filesize = self::_detectFileSize(self::$_aFiles[$sVarName]);
                self::$_aFiles[$sVarName]['size'] = $filesize;
            }
        }

        self::_prepareFileAjax();

        return true;
    }

    /**
     * [_prepareFileAjax description]
     * @return [type] [description]
     */
    private static function _prepareFileAjax(){

        $aHeader = self::_translateHeader();

        if( array_key_exists( 'x-file-name', $aHeader)){

            $sVarName = $aHeader['x-input-name'];

            self::$_aFiles[$sVarName]['name']      = $aHeader['x-file-name'];

            self::$_aFiles[$sVarName]['validated'] = false;
            self::$_aFiles[$sVarName]['received']  = false;
            self::$_aFiles[$sVarName]['filtered']  = false;

            self::$_aFiles[$sVarName]['transfert'] = 'ajax';
            self::$_aFiles[$sVarName]['source']    = file_get_contents('php://input');

            self::$_aFiles[$sVarName]['type']      = $aHeader['x-file-type'];

            self::$_aFiles[$sVarName]['size']      = $aHeader['x-file-size'];
        }

        return true;
    }

    /**
     * [upload description]
     * @return [type] [description]
     */
    public static function upload(){

        self::_prepareFiles();

        $_aFiles = self::getFile();

        if( count( $_aFiles)==0){
            return true;
        }

    	foreach( $_aFiles as $sVarName => $aFile){

           $aFile['name'] = self::_cleanName( $aFile['name']);

	    	foreach( self::_getFilter() as $oFilter){
	            $aFile['name'] = $oFilter->executeFilter( $aFile['name']);
	        }

	        $aDestinations = self::getDestination();

            if( array_key_exists( $sVarName, $aDestinations)){
                $sFolder   = $aDestinations[$sVarName];
            }else{
                $sFolder   = self::$_sFolder;
            }

            $sDestination = $sFolder.$aFile['name'];

            if( file_exists( $sDestination)){
                $sDestination = self::_buildName($sDestination);
            }

            if($aFile['transfert'] == 'ajax'){
               $bTransfred = self::_transfertFilePutContents( $sDestination, $aFile);
            }else{
               $bTransfred = self::_transfertMoveUploadFile( $sDestination, $aFile);
            }

            if( $bTransfred){
                $aInfoFile = pathinfo( $sDestination);
                $sFolder   = $aInfoFile['dirname'];
                $name      = $aInfoFile['basename'];

                self::$_aFiles[$sVarName]['received']     = true;
                self::$_aFiles[$sVarName]['destination']  = $sFolder.DS;
                self::$_aFiles[$sVarName]['name']         = $name;
            }
    	}

    	return true;
    }

    /**
     * [getFile description]
     * @param  [type] $sName [description]
     * @return [type]        [description]
     */
    public static function getFile( $sName=NULL){
    	if( is_null($sName)){
    		return self::$_aFiles;
    	}else{
    		return self::$_aFiles[$sName];
    	}
    }

    /**
     * [addFilter description]
     * @param [type] $oFilter [description]
     */
    public static function addFilter( $oFilter){
        self::$_aFilters[] = $oFilter;
        return true;
    }

    /**
     * [getDestination description]
     * @return [type] [description]
     */
    public static function getDestination(){
        return self::$_aDestination;
    }

    /**
     * [setDestination description]
     * @param array $aDestination [description]
     */
    public static function setDestination( $aDestination = array()){
        foreach ( $aDestination as $sName => $sDestination) {
            self::addDestination( $sDestination, $sName);
        }
        return true;
    }

    /**
     * [setDestination description]
     * @param string $sDestination [description]
     */
    public static function addDestination( $sDestination = '', $sOpt = '*'){

        $sDestination =rtrim( $sDestination, '/').'/';

        if($sOpt == '*'){
            self::$_sFolder = $sDestination;
            return true;
        }

        self::$_aDestination[$sOpt] = $sDestination;

        return true;
    }
    /**
     * [_buildName description]
     * @param  [type]  $sDestination [description]
     * @param  integer $iCounter     [description]
     * @return [type]                [description]
     */
    private static function _buildName( $sDestination, $iCounter = 1){

        $aInfoFile = pathinfo($sDestination);

        $sNewDestination = $aInfoFile['dirname'].'/'.$aInfoFile['filename'].'-'. $iCounter.".".$aInfoFile['extension'];

        if( file_exists( $sNewDestination)){
            $iCounter++;
            return self::_buildName( $sDestination, $iCounter);
        }else{
            return $sNewDestination;
        }
    }

    /**
     * [_translateHeader description]
     * @return [type] [description]
     */
    private static function _translateHeader(){

        foreach ($_SERVER as $sKey => $mValue){
            if (substr($sKey, 0, 5) == 'HTTP_'){
                $sNewKey = substr($sKey, 5);
                $sNewKey = str_replace('_', ' ', $sNewKey);
                $sNewKey = str_replace(' ', '-',strtolower($sNewKey));
                $aHeader[$sNewKey] = $mValue;
            }
        }
        return $aHeader;
    }

    /**
     * [_detectMimeType description]
     * @param  [type] $aFile [description]
     * @return [type]        [description]
     */
    private static function _detectMimeType( $aFile){

    	if (file_exists($aFile['name'])) {
            $sFile = $aFile['name'];
        } elseif (file_exists($aFile['tmp_name'])) {
            $sFile = $aFile['tmp_name'];
        } else {
            return null;
        }

        if ( function_exists( 'mime_content_type')) {
            $sMimeType = mime_content_type($sFile);
        }

    	return $sMimeType;
    }

    /**
     * [_getFilter description]
     * @return [type] [description]
     */
    private static function _getFilter(){
    	return self::$_aFilters;
    }

    /**
     * [_detectFileSize description]
     * @param  [type] $aFile [description]
     * @return [type]        [description]
     */
    private static function _detectFileSize( $aFile){

    	if (file_exists($aFile['name'])) {
            $filename = $aFile['name'];
        } elseif (file_exists($aFile['tmp_name'])) {
            $filename = $aFile['tmp_name'];
        } else {
            return null;
        }

        $iFileSize = filesize($filename);

    	return $iFileSize;
    }

    /**
     * [cleanName description]
     * @param  string $sFilename [description]
     * @return [type]            [description]
     */
    private static function _cleanName( $sFilename = ""){

       $aInfoFile  = pathinfo($sFilename);
       $sExt       = $aInfoFile['extension'];
       $sFilename  = $aInfoFile['filename'];

       $sFilename = str_replace( ' ','_',$sFilename);
       $var       = preg_replace( "#[^a-z0-9_\-]#i","-",strtolower($sFilename));
       $var       = preg_replace( "#\-{1,}#i","-",strtolower($var));

       return $var.'.'.$sExt;
    }

    /**
     * [_transfertMoveUploadFile description]
     * @param  [type] $sDestination [description]
     * @param  [type] $aFile        [description]
     * @return [type]               [description]
     */
     private static function _transfertMoveUploadFile( $sDestination, $aFile){
        return move_uploaded_file( $aFile['tmp_name'], $sDestination);
    }

    /**
     * [_transfertFilePutContents description]
     * @param  [type] $sDestination [description]
     * @param  [type] $aFile        [description]
     * @return [type]               [description]
     */
    private static function _transfertFilePutContents( $sDestination, $aFile){
        return file_put_contents( $sDestination, $aFile['source']);
    }
}