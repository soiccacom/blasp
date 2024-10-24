<?php
namespace Blaspsoft\Blasp;

class ProfanityDetector
{
    private array $profanityExpressions;
    private array $falsePositives;
    private ?string $language;

    public function __construct(array $profanityExpressions, array $falsePositives, ?string $language = null)
    {
        $this->profanityExpressions = $profanityExpressions;
        $this->falsePositives = $falsePositives;
    }

    public function getProfanityExpressions(): array
    {
        uksort($this->profanityExpressions, function($a, $b) {
            return strlen($b) - strlen($a);  // Sort by length, descending
        });
        return $this->profanityExpressions;
    }

    public function isFalsePositive(string $word): bool
    {
        return in_array(strtolower($word), $this->falsePositives, true);
    }
}
