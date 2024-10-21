<?php

namespace Blaspsoft\Blasp;

use Exception;

class BlaspService extends BlaspExpressionService
{
    /**
     * The incoming string to check for profanities.
     *
     * @var string
     */
    public string $sourceString;

    /**
     * The sanitised string with profanities masked.
     *
     * @var string
     */
    public string $cleanString;

    /**
     * A boolean value indicating if the incoming string
     * contains any profanities.
     *
     * @var bool
     */
    public bool $hasProfanity = false;

    /**
     * The number of profanities found in the incoming string.
     *
     * @var int
     */
    public int $profanitiesCount = 0;

    /**
     * An array of unique profanities found in the incoming string.
     *
     * @var array
     */
    public array $uniqueProfanitiesFound = [];

    /**
     * Initialise the class and parent class.
     * 
     */
    public function __construct()
    {
        parent::__construct();
        return $this;
    }

    /**
     * @param string $string
     * @return $this
     * @throws Exception
     */
    public function check(string $string): self
    {
        if (empty($string)) {

            throw new Exception('No string to check');
        }

        $this->sourceString = $string;

        $this->cleanString = $string;

        $this->handle();

        return $this;
    }

    /**
     * Check if the incoming string contains any profanities, set property
     * values and mask the profanities within the incoming string.
     *
     * @return $this
     */
    private function handle(): self
    {
        foreach ($this->profanityExpressions as $profanity => $expression) {

            while ($this->stringHasProfanity($expression)) {

                $this->hasProfanity = true;

                if (!in_array($profanity, $this->uniqueProfanitiesFound)) {
                    $this->uniqueProfanitiesFound[] = $profanity;
                }

                $this->generateProfanityReplacement($expression);
            }

        }

        return $this;
    }

    /**
     * Check if the incoming string contains any profanities.
     * 
     * @param string $profanity
     * @return bool
     */
    private function stringHasProfanity(string $profanity): bool
    {
        return preg_match($profanity, $this->cleanString) === 1;
    }

    /**
     * Mask the profanities found in the incoming string.
     * 
     * @param string $profanity
     * @return string
     */
    private function generateProfanityReplacement(string $profanity): string
    {
        preg_match_all($profanity, $this->cleanString, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[0] as $match) {
            $start = $match[1];
            $length = mb_strlen($match[0], 'UTF-8');
            $replacement = str_repeat("*", $length);

            $this->cleanString = substr_replace($this->cleanString, $replacement, $start, $length);
            
            $this->profanitiesCount++;
        }
    }

    /**
     * Get the incoming string.
     * 
     * @return string
     */
    public function getSourceString(): string
    {
        return $this->sourceString;
    }

    /**
     * Get the clean string with profanities masked.
     * 
     * @return string
     */
    public function getCleanString(): string
    {
        return $this->cleanString;
    }

    /**
     * Get a boolean value indicating if the incoming
     * string contains any profanities.
     * 
     * @return bool
     */
    public function hasProfanity(): bool
    {
        return $this->hasProfanity;
    }

    /**
     * Get the number of profanities found in the incoming string.
     * 
     * @return int
     */
    public function getProfanitiesCount(): int
    {
        return $this->profanitiesCount;
    }

    /**
     * Get the unique profanities found in the incoming string.
     * 
     * @return array
     */
    public function getUniqueProfanitiesFound(): array
    {
        return $this->uniqueProfanitiesFound;
    }
}