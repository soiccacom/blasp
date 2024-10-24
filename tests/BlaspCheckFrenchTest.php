<?php


namespace Blaspsoft\Blasp\Tests;

use Blaspsoft\Blasp\BlaspService;
use Exception;
use Illuminate\Support\Facades\Config;

class BlaspCheckFrenchTest extends TestCase
{

    protected $blaspService;

    public function setUp(): void
    {
        parent::setUp();
        Config::set('blasp.profanities', ['fr' => ['putain', 'connasse', 'c0nn4ss3', 'putain', 'connard', 'merdique', 'bordel', 'foutre', 'Putain', 'merde']]);
        Config::set('blasp.separators', [' ', '-', '_']);
        Config::set('blasp.false_positives', ['fr' => ['passeur', 'classe']]);
        Config::set('blasp.substitutions', [
            '/a/' => ['a', '4', '@', 'Á', 'á', 'À', 'Â', 'à', 'Â', 'â', 'Ä', 'ä', 'Ã', 'ã', 'Å', 'å', 'æ', 'Æ', 'α', 'Δ', 'Λ', 'λ'],
            '/b/' => ['b', '8', '\\', '3', 'ß', 'Β', 'β'],
            '/c/' => ['c', 'Ç', 'ç', 'ć', 'Ć', 'č', 'Č', '¢', '€', '<', '(', '{', '©'],
            '/d/' => ['d', '\\', ')', 'Þ', 'þ', 'Ð', 'ð'],
            '/e/' => ['e', '3', '€', 'È', 'è', 'É', 'é', 'Ê', 'ê', 'ë', 'Ë', 'ē', 'Ē', 'ė', 'Ė', 'ę', 'Ę', '∑'],
            '/f/' => ['f', 'ƒ'],
            '/g/' => ['g', '6', '9'],
            '/h/' => ['h', 'Η'],
            '/i/' => ['i', '!', '|', ']', '[', '1', '∫', 'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï', 'ī', 'Ī', 'į', 'Į'],
            '/j/' => ['j'],
            '/k/' => ['k', 'Κ', 'κ'],
            '/l/' => ['l', '!', '|', ']', '[', '£', '∫', 'Ì', 'Í', 'Î', 'Ï', 'ł', 'Ł'],
            '/m/' => ['m'],
            '/n/' => ['n', 'η', 'Ν', 'Π', 'ñ', 'Ñ', 'ń', 'Ń'],
            '/o/' => ['o', '0', 'Ο', 'ο', 'Φ', '¤', '°', 'ø', 'ô', 'Ô', 'ö', 'Ö', 'ò', 'Ò', 'ó', 'Ó', 'œ', 'Œ', 'ø', 'Ø', 'ō', 'Ō', 'õ', 'Õ'],
            '/p/' => ['p', 'ρ', 'Ρ', '¶', 'þ'],
            '/q/' => ['q'],
            '/r/' => ['r', '®'],
            '/s/' => ['s', '5', '$', '§', 'ß', 'Ś', 'ś', 'Š', 'š'],
            '/t/' => ['t', 'Τ', 'τ'],
            '/u/' => ['u', 'υ', 'µ', 'û', 'ü', 'ù', 'ú', 'ū', 'Û', 'Ü', 'Ù', 'Ú', 'Ū'],
            '/v/' => ['v', 'υ', 'ν'],
            '/w/' => ['w', 'ω', 'ψ', 'Ψ'],
            '/x/' => ['x', 'Χ', 'χ'],
            '/y/' => ['y', '¥', 'γ', 'ÿ', 'ý', 'Ÿ', 'Ý'],
            '/z/' => ['z', 'Ζ', 'ž', 'Ž', 'ź', 'Ź', 'ż', 'Ż'],

        ]);
        $this->blaspService = new BlaspService('fr');
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
