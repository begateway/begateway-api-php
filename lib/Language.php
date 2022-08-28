<?php

declare(strict_types=1);

namespace BeGateway;

class Language
{
    public static function getSupportedLanguages(): array
    {
        return [
            'en',
            'es',
            'tr',
            'de',
            'it',
            'ru',
            'zh',
            'fr',
            'da',
            'fi',
            'no',
            'pl',
            'sv',
            'ja',
            'be',
            'ka',
            'uk',
            'lv',
            'az',
            'ro',
        ];
    }

    public static function getDefaultLanguage(): string
    {
        return 'en';
    }
}
