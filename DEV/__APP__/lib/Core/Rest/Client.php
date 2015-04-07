<?php

namespace Core\Rest;

use Core\Rest\Client\Stream  as Stream,
    Core\Rest\Client\Curl  as Curl;


class Client
{
   /**
    * [__construct description]
    */
  private static $_instance = array();

  /**
   * [__construct description]
   */
  private function __construct() {}

  /**
   * @method setDefaultLayer description
   * @param Array $mParams
   */
  private static function _buildLayer( $mParams)
  {
      $sType = strtolower( $mParams);

      switch ($sType) {
          default:
          case 'curl':
              $oStream = new Curl();
              break;

          case 'stream':
              $oStream = new Stream();
              break;
      }

      return $oStream;
  }

  /**
   * @method _buildNameReferer description
   * @param  Array $mParams
   * @return String
   */
  private static function _buildNameReferer($mParams)
  {
      $sDaoReferer = md5( strtolower( $mParams));

      return $sDaoReferer;
  }

  /**
   * @method getInstance description
   * @param  Array  $mParams
   * @return Object
   */
  public static function getInstance( $mParams  = 'curl')
  {
      $sReferer = self::_buildNameReferer( $mParams );

      if ( !isset( self::$_instance[$sReferer])) {
          self::$_instance[$sReferer] = self::_buildLayer( $mParams );
      }

      return self::$_instance[$sReferer];
  }
}
