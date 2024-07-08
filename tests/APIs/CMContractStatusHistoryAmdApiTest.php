<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractStatusHistoryAmd;

class CMContractStatusHistoryAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_status_history_amd()
    {
        $cMContractStatusHistoryAmd = CMContractStatusHistoryAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_status_history_amds', $cMContractStatusHistoryAmd
        );

        $this->assertApiResponse($cMContractStatusHistoryAmd);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_status_history_amd()
    {
        $cMContractStatusHistoryAmd = CMContractStatusHistoryAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_status_history_amds/'.$cMContractStatusHistoryAmd->id
        );

        $this->assertApiResponse($cMContractStatusHistoryAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_status_history_amd()
    {
        $cMContractStatusHistoryAmd = CMContractStatusHistoryAmd::factory()->create();
        $editedCMContractStatusHistoryAmd = CMContractStatusHistoryAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_status_history_amds/'.$cMContractStatusHistoryAmd->id,
            $editedCMContractStatusHistoryAmd
        );

        $this->assertApiResponse($editedCMContractStatusHistoryAmd);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_status_history_amd()
    {
        $cMContractStatusHistoryAmd = CMContractStatusHistoryAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_status_history_amds/'.$cMContractStatusHistoryAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_status_history_amds/'.$cMContractStatusHistoryAmd->id
        );

        $this->response->assertStatus(404);
    }
}
