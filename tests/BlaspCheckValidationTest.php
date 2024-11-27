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
        $data = ['message' => 'This is a clean message.'];

        $rules = ['message' => 'blasp_check'];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->passes());
    }

    /**
     * Test validation fails with profane text.
     *
     * @return void
     */
    public function test_blasp_check_validation_fails_with_profanity()
    {
        $data = ['message' => 'This is a fucking message.'];

        $rules = ['message' => 'blasp_check'];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
    }
}
