<?php

namespace Blaspsoft\Blasp\Normalizers;

use Blaspsoft\Blasp\Abstracts\StringNormalizer;

class Normalize
{
    public static function getLanguageNormalizerInstance(string $language): StringNormalizer
    {
        switch($language){
            case 'fr':
                return new FrenchStringNormalizer();
            default:
                return new EnglishStringNormalizer();
        }
    }
}