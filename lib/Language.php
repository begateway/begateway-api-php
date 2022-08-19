<?php

namespace BeGateway;

class Language
{
    public static function getSupportedLanguages()
    {
        return [
          'en', 'es', 'tr', 'de', 'it', 'ru', 'zh', 'fr', 'da', 'fi', 'no', 'pl', 'sv', 'ja', 'be', 'ka', 'uk',
        ];
    }

    public static function getDefaultLanguage()
    {
        return 'en';
    }
}
