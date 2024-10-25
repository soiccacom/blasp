<?php


namespace Blaspsoft\Blasp\Tests;

use Blaspsoft\Blasp\BlaspService;
use Exception;

class BlaspCheckFrenchTest extends TestCase
{

    protected $blaspService;

    public function setUp(): void
    {
        parent::setUp();
        $this->blaspService = new BlaspService('fr');
    }

    /**
     * @throws Exception
     */
    public function testInvalidLanguage():void
    {
        $this->expectExceptionMessage('Unsupported language.');
        $blaspService = new BlaspService('es');
        $blaspService->check('This for test the language');
    }

    public function french_test_data_provider(): array
    {
        return [
            'test_real_blasp_service' => [
                'string' => 'Ceci est une putain de phrase',
                'hasProfanity' => true,
                'count' => 1,
                'expectedText' => 'Ceci est une ****** de phrase',
            ],
            'test_straight_match' => [
                'string' => 'Ceci est une connasse',
                'hasProfanity' => true,
                'count' => 1,
                'expectedText' => 'Ceci est une ********',
            ],
            'test_substitution_match' => [
                'string' => 'Ceci est une c0nn4ss3',
                'hasProfanity' => true,
                'count' => 1,
                'expectedText' => 'Ceci est une ********',
            ],
            'test_obscured_match' => [
                'string' => 'Ceci est une c-o-n-n-a-s-s-e',
                'hasProfanity' => true,
                'count' => 1,
                'expectedText' => 'Ceci est une ***************',
            ],
            'test_doubled_match' => [
                'string' => 'Ceci est une ccoonnaassee',
                'hasProfanity' => true,
                'count' => 1,
                'expectedText' => 'Ceci est une ************',
            ],
            'test_combination_match' => [
                'string' => 'Ceci est une c-ooonn4ss3',
                'hasProfanity' => true,
                'count' => 1,
                'expectedText' => 'Ceci est une ***********',
            ],
            'test_multiple_profanities_no_spaces' => [
                'string' => 'merdiqueputain foutre',
                'hasProfanity' => true,
                'count' => 3,
                'expectedText' => '************** ******',
            ],
            'test_multiple_profanities' => [
                'string' => 'Ceci est une putain de phrase de connard !',
                'hasProfanity' => true,
                'count' => 2,
                'expectedText' => 'Ceci est une ****** de phrase de ******* !',
            ], 'test_scunthorpe_problem' => [
                'string' => 'je suis un passeur',
                'hasProfanity' => false,
                'count' => 0,
                'expectedText' => 'je suis un passeur',
            ], 'test_paragraph' => [
                'string' => "Ce projet est tellement merdique. C'est un vrai bordel, et personne n'en a rien à foutre.",
                'hasProfanity' => true,
                'count' => 3,
                'expectedText' => "Ce projet est tellement ********. C'est un vrai ******, et personne n'en a rien à ******.",
            ],
        ];
    }


    /**
     * @throws Exception
     * @dataProvider french_test_data_provider
     */
    public function test_language_french(string $string, bool $hasProfanity, int $profanitiesCount, string $expectedText): void
    {
        $result = $this->blaspService->check($string);

        $this->assertEquals($hasProfanity, $result->hasProfanity);
        $this->assertSame($profanitiesCount, $result->profanitiesCount);
        $this->assertSame($expectedText, $result->cleanString);
    }

    /**
     * @throws Exception
     */
    public function test_paragraph_french(): void
    {
        $paragraph = "Ce projet est tellement merdique. C'est un vrai bordel, et personne n'en a rien à foutre.";

        $result = $this->blaspService->check($paragraph, 'fr');

        $expectedOutcome = "Ce projet est tellement ********. C'est un vrai ******, et personne n'en a rien à ******.";
        $this->assertTrue($result->hasProfanity);
        $this->assertSame(3, $result->profanitiesCount);
        $this->assertCount(3, $result->uniqueProfanitiesFound);
        $this->assertSame($expectedOutcome, $result->cleanString);
    }
}
