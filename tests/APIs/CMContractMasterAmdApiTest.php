<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractMasterAmd;

class CMContractMasterAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_master_amd()
    {
        $cMContractMasterAmd = CMContractMasterAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_master_amds', $cMContractMasterAmd
        );

        $this->assertApiResponse($cMContractMasterAmd);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_master_amd()
    {
        $cMContractMasterAmd = CMContractMasterAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_master_amds/'.$cMContractMasterAmd->id
        );

        $this->assertApiResponse($cMContractMasterAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_master_amd()
    {
        $cMContractMasterAmd = CMContractMasterAmd::factory()->create();
        $editedCMContractMasterAmd = CMContractMasterAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_master_amds/'.$cMContractMasterAmd->id,
            $editedCMContractMasterAmd
        );

        $this->assertApiResponse($editedCMContractMasterAmd);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_master_amd()
    {
        $cMContractMasterAmd = CMContractMasterAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_master_amds/'.$cMContractMasterAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_master_amds/'.$cMContractMasterAmd->id
        );

        $this->response->assertStatus(404);
    }
}
