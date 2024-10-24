<?php
namespace Blaspsoft\Blasp\Tests;

use Illuminate\Support\Facades\Validator;

class BlaspCheckValidationTest extends TestCase
{
    /**
     * Test validation passes with clean text.
     *
     * @return void
     */
    public function test_blasp_check_validation_passes_with_clean_text()
    {
        // Texte sans profanité
        $data = ['message' => 'This is a clean message.'];

        // Validation avec la règle 'blasp_check'
        $rules = ['message' => 'blasp_check'];

        // Exécuter la validation
        $validator = Validator::make($data, $rules);

        // Vérifier que la validation passe
        $this->assertTrue($validator->passes());
    }

    /**
     * Test validation fails with profane text.
     *
     * @return void
     */
    public function test_blasp_check_validation_fails_with_profanity()
    {
        // Texte avec profanité
        $data = ['message' => 'This is a fucking message.'];

        // Validation avec la règle 'blasp_check'
        $rules = ['message' => 'blasp_check'];

        // Exécuter la validation
        $validator = Validator::make($data, $rules);

        // Vérifier que la validation échoue
        $this->assertTrue($validator->fails());
    }

    /**
     * Test validation passes with clean French text.
     *
     * @return void
     */
    public function test_blasp_check_validation_passes_with_clean_french_text()
    {
        // Texte sans profanité en français
        $data = ['message' => 'Ceci est un message propre.'];

        // Validation avec la règle 'blasp_check'
        $rules = ['message' => 'blasp_check:fr'];

        // Exécuter la validation
        $validator = Validator::make($data, $rules);

        // Vérifier que la validation passe
        $this->assertTrue($validator->passes());
    }

    /**
     * Test validation fails with profane French text.
     *
     * @return void
     */
    public function test_blasp_check_validation_fails_with_french_profanity()
    {
        // Texte avec profanité en français
        $data = ['message' => 'Ceci est un message de merdique.'];

        // Validation avec la règle 'blasp_check'
        $rules = ['message' => 'blasp_check:fr'];

        // Exécuter la validation
        $validator = Validator::make($data, $rules);

        // Vérifier que la validation échoue
        $this->assertTrue($validator->fails());
    }
}
