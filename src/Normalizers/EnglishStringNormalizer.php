<?php

namespace Blaspsoft\Blasp\Normalizers;

use Blaspsoft\Blasp\Abstracts\StringNormalizer;

class EnglishStringNormalizer extends StringNormalizer
{

    public function normalize(string $string): string
    {
        return $string;
    }
}