<?php
namespace beGateway;

class Language {
  public static function getSupportedLanguages() {
    return array('en','es','tr','de','it','ru','zh','fr','da','fi','no','pl','sv');
  }
  public static function getDefaultLanguage() {
    return 'en';
  }
}
?>
