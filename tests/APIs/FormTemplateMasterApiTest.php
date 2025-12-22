<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\FormTemplateMaster;

class FormTemplateMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_form_template_master()
    {
        $formTemplateMaster = FormTemplateMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/form_template_masters', $formTemplateMaster
        );

        $this->assertApiResponse($formTemplateMaster);
    }

    /**
     * @test
     */
    public function test_read_form_template_master()
    {
        $formTemplateMaster = FormTemplateMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/form_template_masters/'.$formTemplateMaster->id
        );

        $this->assertApiResponse($formTemplateMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_form_template_master()
    {
        $formTemplateMaster = FormTemplateMaster::factory()->create();
        $editedFormTemplateMaster = FormTemplateMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/form_template_masters/'.$formTemplateMaster->id,
            $editedFormTemplateMaster
        );

        $this->assertApiResponse($editedFormTemplateMaster);
    }

    /**
     * @test
     */
    public function test_delete_form_template_master()
    {
        $formTemplateMaster = FormTemplateMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/form_template_masters/'.$formTemplateMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/form_template_masters/'.$formTemplateMaster->id
        );

        $this->response->assertStatus(404);
    }
}
