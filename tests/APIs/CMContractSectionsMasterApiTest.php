<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractSectionsMaster;

class CMContractSectionsMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_sections_master()
    {
        $cMContractSectionsMaster = CMContractSectionsMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_sections_masters', $cMContractSectionsMaster
        );

        $this->assertApiResponse($cMContractSectionsMaster);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_sections_master()
    {
        $cMContractSectionsMaster = CMContractSectionsMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_sections_masters/'.$cMContractSectionsMaster->id
        );

        $this->assertApiResponse($cMContractSectionsMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_sections_master()
    {
        $cMContractSectionsMaster = CMContractSectionsMaster::factory()->create();
        $editedCMContractSectionsMaster = CMContractSectionsMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_sections_masters/'.$cMContractSectionsMaster->id,
            $editedCMContractSectionsMaster
        );

        $this->assertApiResponse($editedCMContractSectionsMaster);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_sections_master()
    {
        $cMContractSectionsMaster = CMContractSectionsMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_sections_masters/'.$cMContractSectionsMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_sections_masters/'.$cMContractSectionsMaster->id
        );

        $this->response->assertStatus(404);
    }
}
