<?php

namespace Blaspsoft\Blasp\Abstracts;

abstract class StringNormalizer
{

    /**
     * Package active language
     *
     * @var string|null
     */
    private ?string $language;


    public function __construct(?string $language = null)
    {
        $this->language = $language;
    }

    abstract public function normalize(string $string): string;

}