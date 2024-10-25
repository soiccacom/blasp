<?php

namespace Blaspsoft\Blasp\Abstracts;

abstract class StringNormalizer
{

    abstract public function normalize(string $string): string;

}