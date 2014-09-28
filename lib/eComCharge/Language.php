<?php
namespace eComCharge;

class Language {
  public static function getSupportedLanguages() {
    return array('en','es','tr','de','it','ru','zh','fr');
  }
  public static function getDefaultLanguage() {
    return 'en';
  }
}
?>
