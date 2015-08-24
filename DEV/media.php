<?php
session_start();

// INITIALISATION DES DIFFERENT NAMESPACE
  use Main\App\App                as App,
      Main\Conf\Conf              as Conf,
      Core\Header\Header          as Header,
      Main\Parameter\Parameter    as Parameter,
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
              'query'  => 'picture-1',
              'display'=> 'jpg',
         ));

// MISE EN PLACE DES VARIABLES DE L'APP

  App::set( 'space', 'media');
  require_once( PATH_APP.'_bootstrap.php');

// EXECUTION DES CONTROLLEURS

  Controller::setQuery( Parameter::get()->get('query'));

  //redimentionne dans un conteneur media, en couvrant la zone avec decalage et ajoute un effet
  Controller::then('filter-[:effect:]/cover-[:marge:]/[:width:]x[:height:]/[:file:]', function(){
      Controller::addCode( 'resize');
  });

  //croppe à un point donné, une zone donnée media et ajoute un effet
  Controller::then('filter-[:effect:]/[:x:]x[:y:]/[:width:]x[:height:]/[:file:]', function(){
      Controller::addCode( 'croppe');
  });

  //redimentionne dans un conteneur media et ajoute un effet
  Controller::then('filter-[:effect:]/[:mode:]/[:width:]x[:height:]/[:file:]', function(){
      Controller::addCode( 'resize');
  });

  //redimentionne dans un conteneur en mode cover media et ajoute un effet
  Controller::then('filter-[:effect:]/[:width:]x[:height:]/[:file:]', function(){
      Controller::addCode( 'resize');
  });

  Controller::then('cover-[:marge:]/[:width:]x[:height:]/[:file:]', function(){
      Controller::addCode( 'resize');
  });

  //croppe à un point donné, une zone donnée media
  Controller::then('[:x:]x[:y:]/[:width:]x[:height:]/[:file:]', function(){
      Controller::addCode( 'croppe');
  });

  //redimentionne dans un conteneur media
  Controller::then('[:mode:]/[:width:]x[:height:]/[:file:]', function(){
      Controller::addCode( 'resize');
  });

  //redimentionne dans un conteneur en mode cover media
  Controller::then('[:width:]x[:height:]/[:file:]', function(){
      Controller::addCode( 'resize');
  });

  //normal media
  Controller::then('[:file:]', function(){
      Controller::addCode( 'read');
  });

  Controller::prependCode( 'global');

  if ( count( $aError = Controller::exec()) == 0) {

    if( is_readable( App::get('path-media'))){
      Header::exec();
      readfile( App::get('path-media'));
    }else{
      Header::setStatus(404);
      Header::exec();
    }
  }











