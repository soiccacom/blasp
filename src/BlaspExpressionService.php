<?php

namespace Blaspsoft\Blasp;

abstract class BlaspExpressionService
{
    /**
     * Value used as a the separator placeholder.
     * 
     * @var string
     */
    const SEPARATOR_PLACEHOLDER = '{!!}';

    /**
     * A list of possible character separators.
     *
     * @var array
     */
    private array $separators;

    /**
     * A list of possible character substitutions.
     *
     * @var array
     */
    private array $substitutions;

    /**
     * A list of profanities to check against.
     *
     * @var array
     */
    public array $profanities;

    /**
     * Escaped separator characters
     */
    private array $escapedSeparatorCharacters = [
        '\s',
    ];

    /**
     * An array containing all profanities, substitutions
     * and separator variants.
     *
     * @var array
     */
    protected array $profanityExpressions;

    /**
     * An array of separator expression profanities
     *
     * @var array
     */
    protected array|string $separatorExpression;

    /**
     * An array of character expression profanities
     *
     * @var array
     */
    protected array $characterExpressions;

    public function __construct()
    {
        $this->loadConfiguration();

        $this->separatorExpression = $this->generateSeparatorExpression();

        $this->characterExpressions = $this->generateSubstitutionExpression();

        $this->generateProfanityExpressionArray();
    }

    /**
     * Load Profanities, Separators and Substitutions
     * from config file.
     *
     */
    private function loadConfiguration()
    {
        $this->profanities = config('blasp.profanities');
        $this->separators = config('blasp.separators');
        $this->substitutions = config('blasp.substitutions');
    }

    /**
     * @return string
     */
    private function generateSeparatorExpression(): string
    {
        return $this->generateEscapedExpression($this->separators, $this->escapedSeparatorCharacters);
    }

    /**
     * @return array
     */
    private function generateSubstitutionExpression(): array
    {
        $characterExpressions = [];

        foreach ($this->substitutions as $character => $substitutions) {

            $characterExpressions[$character] = $this->generateEscapedExpression($substitutions, [], '+?') . self::SEPARATOR_PLACEHOLDER;
        }

        return $characterExpressions;
    }

    /**
     * @param array $characters
     * @param array $escapedCharacters
     * @param string $quantifier
     * @return string
     */
    private function generateEscapedExpression(array $characters = [], array $escapedCharacters = [], string $quantifier = '*?'): string
    {
        $regex = $escapedCharacters;

        foreach ($characters as $character) {

            $regex[] = preg_quote($character, '/');
        }

        return '[' . implode('', $regex) . ']' . $quantifier;
    }

    /**
     * Generate expressions foreach of the profanities
     * and order the array longest to shortest.
     *
     */
    private function generateProfanityExpressionArray()
    {
        $profanityCount = count($this->profanities);

        for ($i = 0; $i < $profanityCount; $i++) {

            $this->profanityExpressions[$this->profanities[$i]] = $this->generateProfanityExpression($this->profanities[$i]);
        }

        uksort($this->profanityExpressions, function($a, $b) {

            return strlen($b) - strlen($a);
        });
    }

    /**
     * Generate a regex expression foreach profanity.
     * 
     * @param $profanity
     * @return string
     */
    private function generateProfanityExpression($profanity): string
    {
        $expression = '/' . preg_replace(array_keys($this->characterExpressions), array_values($this->characterExpressions), $profanity) . '(?:s?)?\b/i';

        return str_replace(self::SEPARATOR_PLACEHOLDER, $this->separatorExpression, $expression);
    }
}