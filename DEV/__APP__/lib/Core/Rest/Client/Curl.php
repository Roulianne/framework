<?php

namespace Core\Rest\Client;

use Core\Rest\Client\Streaming       as Streaming;

class Curl extends Streaming
{

   private $_sId = '';

   /**
    * [__construct description]
    */
   public function __construct(){}


   /**
    * [_createContext description]
    * @param  [type] $pMethod  [description]
    * @param  [type] $aData [description]
    * @return [type]           [description]
    */
   protected function _createContext($pMethod, $aData = null){
     $aOption = array();

     switch( $pMethod){

        case'POST':
          $aOption = array(
                      CURLOPT_POST=>1,
                      CURLOPT_POSTFIELDS=>$aData,
                    );
          break;

        case'PUT':
          $aOption = array(
                      CURLOPT_CUSTOMREQUEST=>"PUT",
                      CURLOPT_POSTFIELDS=>http_build_query($aData),
                    );
          break;

        case'DELETE':
          $aOption = array(
                      CURLOPT_CUSTOMREQUEST=>"DELETE",
                    );
          break;

        case'OPTIONS':
          $aOption = array(
                      CURLOPT_CUSTOMREQUEST=>"OPTIONS",
                    );
          break;

        case'GET':
        default:
          break;

     }

     return $aOption;
   }

   /**
    * [_launch description]
    * @param  [type] $pUrl    [description]
    * @param  [type] $context [description]
    * @return [type]          [description]
    */
   protected function _launch ($pUrl, $context){
        $rCurrent = curl_init();

        curl_setopt( $rCurrent, CURLOPT_URL, $pUrl);

        curl_setopt( $rCurrent, CURLOPT_HEADER, false);
        curl_setopt( $rCurrent, CURLOPT_VERBOSE , true);
        curl_setopt( $rCurrent, CURLOPT_BINARYTRANSFER, true);
        curl_setopt( $rCurrent, CURLOPT_RETURNTRANSFER, true);

        curl_setopt_array( $rCurrent, $context);

        $sResponse = curl_exec( $rCurrent);

        $aResponse = array(
              'content' => $sResponse,
              'header'  => '',
          );

        curl_close( $rCurrent);

        return $aResponse;
   }

}
