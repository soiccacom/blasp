<?php
namespace Blaspsoft\Blasp;

class ProfanityDetector
{

    /**
     * An array containing all profanities, substitutions
     * and separator variants.
     *
     * @var array
     */
    protected array $profanityExpressions;

    /**
     * An array of false positive expressions
     *
     * @var array
     */
    protected array $falsePositives;

    public function __construct(array $profanityExpressions, array $falsePositives)
    {
        $this->profanityExpressions = $profanityExpressions;

        $this->falsePositives = $falsePositives;
    }

    /**
     *  Return an array containing all profanities, substitutions
     *  and separator variants.
     *
     * @return array
     */
    public function getProfanityExpressions(): array
    {
        uksort($this->profanityExpressions, function($a, $b) {
            return strlen($b) - strlen($a);  // Sort by length, descending
        });

        return $this->profanityExpressions;
    }

    /**
     * Determine if an expression is a false positive
     *
     * @param string $word
     * @return bool
     */
    public function isFalsePositive(string $word): bool
    {
        return in_array(strtolower($word), $this->falsePositives, true);
    }
}
