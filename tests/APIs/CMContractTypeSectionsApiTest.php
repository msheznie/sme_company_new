<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractTypeSections;

class CMContractTypeSectionsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_type_sections()
    {
        $cMContractTypeSections = CMContractTypeSections::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_type_sections', $cMContractTypeSections
        );

        $this->assertApiResponse($cMContractTypeSections);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_type_sections()
    {
        $cMContractTypeSections = CMContractTypeSections::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_type_sections/'.$cMContractTypeSections->id
        );

        $this->assertApiResponse($cMContractTypeSections->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_type_sections()
    {
        $cMContractTypeSections = CMContractTypeSections::factory()->create();
        $editedCMContractTypeSections = CMContractTypeSections::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_type_sections/'.$cMContractTypeSections->id,
            $editedCMContractTypeSections
        );

        $this->assertApiResponse($editedCMContractTypeSections);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_type_sections()
    {
        $cMContractTypeSections = CMContractTypeSections::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_type_sections/'.$cMContractTypeSections->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_type_sections/'.$cMContractTypeSections->id
        );

        $this->response->assertStatus(404);
    }
}
