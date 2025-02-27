<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TemplateMaster;

class TemplateMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_template_master()
    {
        $templateMaster = TemplateMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/template_masters', $templateMaster
        );

        $this->assertApiResponse($templateMaster);
    }

    /**
     * @test
     */
    public function test_read_template_master()
    {
        $templateMaster = TemplateMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/template_masters/'.$templateMaster->id
        );

        $this->assertApiResponse($templateMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_template_master()
    {
        $templateMaster = TemplateMaster::factory()->create();
        $editedTemplateMaster = TemplateMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/template_masters/'.$templateMaster->id,
            $editedTemplateMaster
        );

        $this->assertApiResponse($editedTemplateMaster);
    }

    /**
     * @test
     */
    public function test_delete_template_master()
    {
        $templateMaster = TemplateMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/template_masters/'.$templateMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/template_masters/'.$templateMaster->id
        );

        $this->response->assertStatus(404);
    }
}
