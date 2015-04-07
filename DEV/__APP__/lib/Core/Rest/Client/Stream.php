<?php

namespace Core\Rest\Client;

use Core\Rest\Client\Streaming       as Streaming;

class Stream extends Streaming
{
   private $_sBoundary = "";
   private $_sContent  = "";

   /**
    * [__construct description]
    */
   public function __construct(){
      $this->_sBoundary = '--RESTCLIENT'.substr(md5(rand(0,32000)), 0, 10);
   }

   /**
    * [_createContext description]
    * @param  [type] $pMethod  [description]
    * @param  [type] $aData [description]
    * @return [type]           [description]
    */
   protected function _createContext($pMethod, $aData = null){
      $opts = array(
              'http'=>array(
                            'method'=>$pMethod,
                            'header'=> $this->_getHeader(),
                          )
              );

      if ($aData !== null){

         if (is_array($aData)){
            $sContent = $this->_convertDataToSend( $aData);
         }

         $opts['http']['content'] = $sContent;
      }

      return stream_context_create( $opts);
   }

   /**
    * [_convertFileTosend description]
    * @return [type] [description]
    */
   protected function _convertFileTosend(){
    $sContent = "";

    foreach($this->_aFiles as $sKey => $sPathFile){
        $fFileContents = file_get_contents( $sPathFile);
        //$fileHandle    = fopen( $sPathFile, "rb");
        //$fFileContents = stream_get_contents( $fileHandle);
        //fclose($fileHandle);

        $sName = pathinfo( $sPathFile, PATHINFO_BASENAME);
        $iSize = filesize( $sPathFile);

        if ( function_exists('finfo_open')) {
          $finfo     = finfo_open( FILEINFO_MIME_TYPE);
          $sTypeMIME = finfo_file( $finfo, $sPathFile);
        }else{
          $sTypeMIME = mime_content_type( $sPathFile);
        }

        $sContent .= $this->_getSeparator();
        $sContent .= "Content-Disposition: form-data;name=\"{$sKey}\";filename=\"{$sName}\"\r\n";
        $sContent .= "Content-Type: {$sTypeMIME}\r\n";
        $sContent .= "Content-Length: {$iSize}\r\n";
        $sContent .= "\r\n";
        $sContent .= $fFileContents."\r\n";
    }

    //$fileHandle    = fopen( $this->_aFiles['curl'], "rb");
    //$sContent = stream_get_contents( $fileHandle);
    //fclose($fileHandle);

    return $sContent;
   }

   /**
    * [_getHeader description]
    * @return [type] [description]
    */
   protected function _getHeader(){

    if( count($this->_aFiles)>0){
      $sHeader = 'Content-Type: multipart/form-data, boundary='.$this->_sBoundary;
    }else{
      $sHeader = 'Content-type: application/x-www-form-urlencoded';
    }
    return $sHeader."\r\n";
   }

   /**
    * [_convertDataToSend description]
    * @param  [type] $aData [description]
    * @return [type]        [description]
    */
   protected function _convertDataToSend( $aData){
      $sContent = "";

      if( count($this->_aFiles)>0){
        foreach( $aData as $key => $val){
            $sContent .= $this->_getSeparator();
            $sContent .= "Content-Disposition: form-data; name=\"{$key}\"\r\n";
            $sContent .= "\r\n";
            $sContent .= "{$val}\r\n";
        }
        $sContent .= $this->_convertFileTosend();
        $sContent .= $this->_getSeparator(true);
      }else{
        $sContent = http_build_query($aData);
      }

      return $sContent;
  }

  /**
   * [_getSeparator description]
   * @return [type] [description]
   */
  protected function _getSeparator( $bEnd = false){
    $_sBoundary = "--{$this->_sBoundary}";
    if( $bEnd){
      $_sBoundary .= "--";
    }
    return $_sBoundary."\r\n";
  }

   /**
    * [_launch description]
    * @param  [type] $pUrl    [description]
    * @param  [type] $context [description]
    * @return [type]          [description]
    */
   protected function _launch ($pUrl, $context){
        if ( ( $stream = fopen( $pUrl, 'r', false, $context)) !== false){
            $content = stream_get_contents( $stream);
            $header  = stream_get_meta_data( $stream);
            fclose( $stream);
            return array('content'=>$content, 'header'=>$header);
        }else{
            return false;
        }
   }
}
