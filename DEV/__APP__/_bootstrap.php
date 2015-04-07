<?php
use Main\App\App                as App,
    Main\Conf\Conf              as Conf,
    Main\View\View              as View,
    Core\Header\Header          as Header,
    Main\Uploader\Uploader      as Uploader,
    Main\Translator\Translator  as Translator;

// MISE EN PLACE DU BUFFER HEADER
    Header::init();

// CHARGEMENT DES DIFFERENTS LOADER
    App::addLoader( Conf::get('code'),       'code');
    App::addLoader( Conf::get('style'),      'style');
    App::addLoader( Conf::get('module'),     'module');
    App::addLoader( Conf::get('script'),     'script');
    App::addLoader( Conf::get('template'),   'template');
    App::addLoader( Conf::get('structure'),  'structure');
    App::addLoader( Conf::get('traduction'), 'traduction');

// CHARGEMENT DES DIFFERENTS LEXIQUE POUR TRADUCTION
    Translator::addLexique( App::getLoader( 'traduction')->load( 'wording'));
    Translator::addLexique( App::getLoader( 'traduction')->load( 'seo'));
    Translator::addLexique( App::getLoader( 'traduction')->load( 'url'), true);

// CONFIGURATION DES UPLOAD
    Uploader::setDestination( Conf::get('upload'));

// CONFIGURATION DE LA VUE GLOBALE

    // comment traduire les textes
    View::setTranslateMethod( function ($sValue, $mArgs) {
      return Translator::translate( $sValue, $mArgs);
    });

    // comment afficher les fichiers JS
    View::setScriptMethod( function ($aFile) {
      $sResult = '';
      foreach ($aFile as $sFile) {
        $sResult .="<script type=\"text/javascript\" src=\"{$sFile}\"></script>\n";
      }

      return $sResult;
    });

    // comment afficher les fichiers CSS
    View::setStyleMethod( function ($aFile) {
      $sResult = '';
      foreach ($aFile as $sFile) {
        $sResult .="<link href=\"{$sFile}\" rel=\"stylesheet\" type=\"text/css\">\n";
      }

      return $sResult;
    });
