<?php
namespace Blaspsoft\Blasp;

class StringNormalizer
{
    private ?string $language;

    public function __construct(?string $language = null)
    {
        $this->language = $language;
    }

    public function normalize(string $string): string
    {
        if ($this->language === 'fr') {
            return $this->replaceSpecialChars($string);
        }
        return $string;
    }

    private function replaceSpecialChars(string $string): string
    {
        $substitution = config('blasp.substitutions');
        foreach ($substitution as $replacementWithSlashes => $chars) {
            $replacement = trim($replacementWithSlashes, '/');
            $pattern = '/\b[' . implode('', array_map('preg_quote', $chars)) . ']\b/u';
            $string = preg_replace($pattern, $replacement, $string);
        }
        return $string;
    }
}
