<?php
// INITIALISATION DES DIFFERENT NAMESPACE
  use Main\App\App                as App,
      Main\Conf\Conf              as Conf,
      Main\Event\Event            as Event,
      Core\Header\Header          as Header,
      Main\Parameter\Parameter    as Parameter,
      Main\Translator\Translator  as Translator,
      Main\Controller\Controller  as Controller;

// INITIALISATION DES CONSTANT
  define( 'DEBUG'     , true);
  define( 'PS'        , PATH_SEPARATOR);
  define( 'DS'        , DIRECTORY_SEPARATOR);
  define( 'PATH_ROOT' , __DIR__ . DS);
  define( 'PATH_APP'  , PATH_ROOT  . '__APP__'  . DS);
  define( 'PATH_CONF' , PATH_APP   . 'conf' . DS);

  define( 'PATH_LIB'  , PATH_APP   . 'lib'  . DS);
  define( 'PATH_BIN'  , PATH_APP   . 'bin'  . DS);

  define( 'PATH_MOD'  , PATH_BIN   . 'models'  . DS);
  define( 'PATH_ORD'  , PATH_BIN   . 'orders'  . DS);

  define( 'PATH_CORE' , PATH_LIB   . 'Core' . DS);

  if( DEBUG)
    ini_set('display_errors', 'on');

// CHARGEMENT DES LIBRAIRIES
  require_once(
    implode(
      DS,
      array(
        'root'        => PATH_APP,
        'lib_current' => '_Lib.phar',
      )
    )
  );

// RECUPERATION DES CONFIGURATION POUR L'ENSEMBLE DU SITE
  Conf::addSetting( PATH_CONF.'conf.ini');

// CONFIGURATION DE L'URL PAR DEFAULT
  Parameter::setDefault( array(
              'query'  => 'read',
              'display'=> 'json',
              'lang'   => 'fr_FR',
         ));

// MISE EN PLACE DES VARIABLES DE L'APP

  $sLang = ( strpos( $s = Parameter::get()->get('lang'), '_') !== false)? $s : $s.'_'.strtoupper( $s);// arbitraire pour le moment

  App::set( 'space', 'api');
  App::set( 'lang' , $sLang);
  App::set( 'domain_root' , Conf::get('app.http_root'));

  require_once( PATH_APP.'_bootstrap.php');

// EXECUTION DES CONTROLLEURS
  Controller::setQuery( Parameter::get()->get('query'));

  Controller::then('[:model:]/[:ref:]', function(){
      Controller::addCode( 'single');
  });

  Controller::then('[:model:]', function(){
      Controller::addCode( 'multi');
  });




// Affichage

  if ( count( $aError = Controller::exec()) == 0) {
    Header::setStatus(200);
    Header::setContentType( Parameter::get()->get('display'));
    Header::exec();

    if( Parameter::get()->get('display') == 'json'){

      echo json_encode( App::get('data'));

    }else{

      $oXml  = new SimpleXMLElement('<root/>');
      $aFlip = array_flip( (array) App::get('data'));
      array_walk_recursive( $aFlip, array( $oXml, 'addChild'));
      echo $oXml->asXML();
    }

  } else {

    Header::setStatus(200);
    Header::setContentType('json');
    Header::exec();

    echo json_encode( $aError);
  }
