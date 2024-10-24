<?php


namespace Blaspsoft\Blasp\Tests;

use Blaspsoft\Blasp\BlaspService;
use Exception;
use Illuminate\Support\Facades\Config;

class BlaspCheckFrenchTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        Config::set('blasp.profanities', [
            'con', 'connard', 'connasse', 'merde', 'putain', 'enculé', 'salope', 'batard', 'fils de pute', 'pute', 'cul', 'abruti',
            'crétin', 'imbécile', 'ordure', 'débile', 'foutre', 'chiant', 'chiotte', 'bouffon','merdique','bordel'
        ]);
        Config::set('blasp.separators', [' ', '-', '_']);
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
    }

    /**
     * @throws Exception
     */
    public function test_real_blasp_service_french()
    {
        $blaspService = new BlaspService();

        $result = $blaspService->check('Ceci est une putain de phrase','fr');

        $this->assertTrue($result->hasProfanity);
    }

    /**
     * @throws Exception
     */
    public function test_straight_match_french()
    {
        $blaspService = new BlaspService();

        $result = $blaspService->check('Ceci est une connasse', 'fr');

        $this->assertTrue($result->hasProfanity);
        $this->assertSame(1, $result->profanitiesCount);
        $this->assertCount(1, $result->uniqueProfanitiesFound);
        $this->assertSame('Ceci est une ********', $result->cleanString);
    }

    /**
     * @throws Exception
     */
    public function test_substitution_match_french()
    {
        $blaspService = new BlaspService();

        $result = $blaspService->check('Ceci est une c0nn4ss3','fr');

        $this->assertTrue($result->hasProfanity);
        $this->assertSame(1, $result->profanitiesCount);
        $this->assertCount(1, $result->uniqueProfanitiesFound);
        $this->assertSame('Ceci est une ********', $result->cleanString);
    }

    /**
     * @throws Exception
     */
    public function test_multiple_profanities_french()
    {
        $blaspService = new BlaspService();

        $result = $blaspService->check('Ceci est une putain de phrase de connard !');

        $this->assertTrue($result->hasProfanity);
        $this->assertSame(2, $result->profanitiesCount);
        $this->assertCount(2, $result->uniqueProfanitiesFound);
        $this->assertSame('Ceci est une ****** de phrase de ******* !', $result->cleanString);
    }

    /**
     * @throws Exception
     */
    public function test_paragraph_french()
    {
        $blaspService = new BlaspService();

        $paragraph = "Ce projet est tellement merdique. C'est un vrai bordel, et personne n'en a rien à foutre. Putain, je n'en peux plus de cette merde.";

        $result = $blaspService->check($paragraph, 'fr');

        $expectedOutcome = "Ce projet est tellement *******. C'est un vrai ******, et personne n'en a rien à ******. ******, je n'en peux plus de cette *****.";
        $this->assertTrue($result->hasProfanity);
        $this->assertSame(5, $result->profanitiesCount);
        $this->assertCount(5, $result->uniqueProfanitiesFound);
        $this->assertSame($expectedOutcome, $result->cleanString);
    }
}
