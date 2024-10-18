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

            /**
             * Skip if profanity ends with s as this
             * will be picked up using regex.
             **/
            /*if(substr($profanity, -1) == 's') {

                continue;
            }*/

            if($this->stringHasProfanity($expression)) {

                $this->hasProfanity = true;

                $this->uniqueProfanitiesFound[] = $profanity;

                $string = $this->generateProfanityReplacement($expression);

                $this->cleanString = (string) preg_replace($expression, $string, $this->cleanString, -1,$count);

                $this->profanitiesCount += $count;

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

        return str_repeat("*", strlen($matches[0][0][0]));
    }
}