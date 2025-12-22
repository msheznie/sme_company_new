<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CodeConfigurations;

class CodeConfigurationsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_code_configurations()
    {
        $codeConfigurations = CodeConfigurations::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/code_configurations', $codeConfigurations
        );

        $this->assertApiResponse($codeConfigurations);
    }

    /**
     * @test
     */
    public function test_read_code_configurations()
    {
        $codeConfigurations = CodeConfigurations::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/code_configurations/'.$codeConfigurations->id
        );

        $this->assertApiResponse($codeConfigurations->toArray());
    }

    /**
     * @test
     */
    public function test_update_code_configurations()
    {
        $codeConfigurations = CodeConfigurations::factory()->create();
        $editedCodeConfigurations = CodeConfigurations::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/code_configurations/'.$codeConfigurations->id,
            $editedCodeConfigurations
        );

        $this->assertApiResponse($editedCodeConfigurations);
    }

    /**
     * @test
     */
    public function test_delete_code_configurations()
    {
        $codeConfigurations = CodeConfigurations::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/code_configurations/'.$codeConfigurations->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/code_configurations/'.$codeConfigurations->id
        );

        $this->response->assertStatus(404);
    }
}
