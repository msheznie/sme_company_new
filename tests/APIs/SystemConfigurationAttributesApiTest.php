<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\SystemConfigurationAttributes;

class SystemConfigurationAttributesApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_system_configuration_attributes()
    {
        $systemConfigurationAttributes = SystemConfigurationAttributes::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/system_configuration_attributes', $systemConfigurationAttributes
        );

        $this->assertApiResponse($systemConfigurationAttributes);
    }

    /**
     * @test
     */
    public function test_read_system_configuration_attributes()
    {
        $systemConfigurationAttributes = SystemConfigurationAttributes::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/system_configuration_attributes/'.$systemConfigurationAttributes->id
        );

        $this->assertApiResponse($systemConfigurationAttributes->toArray());
    }

    /**
     * @test
     */
    public function test_update_system_configuration_attributes()
    {
        $systemConfigurationAttributes = SystemConfigurationAttributes::factory()->create();
        $editedSystemConfigurationAttributes = SystemConfigurationAttributes::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/system_configuration_attributes/'.$systemConfigurationAttributes->id,
            $editedSystemConfigurationAttributes
        );

        $this->assertApiResponse($editedSystemConfigurationAttributes);
    }

    /**
     * @test
     */
    public function test_delete_system_configuration_attributes()
    {
        $systemConfigurationAttributes = SystemConfigurationAttributes::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/system_configuration_attributes/'.$systemConfigurationAttributes->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/system_configuration_attributes/'.$systemConfigurationAttributes->id
        );

        $this->response->assertStatus(404);
    }
}
