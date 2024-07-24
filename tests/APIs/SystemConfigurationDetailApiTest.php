<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\SystemConfigurationDetail;

class SystemConfigurationDetailApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_system_configuration_detail()
    {
        $systemConfigurationDetail = SystemConfigurationDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/system_configuration_details', $systemConfigurationDetail
        );

        $this->assertApiResponse($systemConfigurationDetail);
    }

    /**
     * @test
     */
    public function test_read_system_configuration_detail()
    {
        $systemConfigurationDetail = SystemConfigurationDetail::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/system_configuration_details/'.$systemConfigurationDetail->id
        );

        $this->assertApiResponse($systemConfigurationDetail->toArray());
    }

    /**
     * @test
     */
    public function test_update_system_configuration_detail()
    {
        $systemConfigurationDetail = SystemConfigurationDetail::factory()->create();
        $editedSystemConfigurationDetail = SystemConfigurationDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/system_configuration_details/'.$systemConfigurationDetail->id,
            $editedSystemConfigurationDetail
        );

        $this->assertApiResponse($editedSystemConfigurationDetail);
    }

    /**
     * @test
     */
    public function test_delete_system_configuration_detail()
    {
        $systemConfigurationDetail = SystemConfigurationDetail::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/system_configuration_details/'.$systemConfigurationDetail->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/system_configuration_details/'.$systemConfigurationDetail->id
        );

        $this->response->assertStatus(404);
    }
}
