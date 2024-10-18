<?php

namespace Blaspsoft\Blasp;

use Exception;

class BlaspService extends BlaspExpressionService
{
    public string $sourceString;

    public string $cleanString;

    public bool $hasProfanity = false;

    public int $profanitiesCount = 0;

    public array $uniqueProfanitiesFound = [];

    /**
     * Blasp constructor.
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
     * @param string $profanity
     * @return bool
     */
    private function stringHasProfanity(string $profanity): bool
    {
        return preg_match($profanity, $this->cleanString) === 1;
    }

    /**
     * @param string $profanity
     * @return string
     */
    private function generateProfanityReplacement(string $profanity): string
    {
        preg_match_all($profanity, $this->cleanString, $matches, PREG_OFFSET_CAPTURE);

        return str_repeat("*", strlen($matches[0][0][0]));
    }
}