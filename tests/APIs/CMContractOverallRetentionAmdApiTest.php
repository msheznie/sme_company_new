<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CMContractOverallRetentionAmd;

class CMContractOverallRetentionAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_c_m_contract_overall_retention_amd()
    {
        $cMContractOverallRetentionAmd = CMContractOverallRetentionAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/c_m_contract_overall_retention_amds', $cMContractOverallRetentionAmd
        );

        $this->assertApiResponse($cMContractOverallRetentionAmd);
    }

    /**
     * @test
     */
    public function test_read_c_m_contract_overall_retention_amd()
    {
        $cMContractOverallRetentionAmd = CMContractOverallRetentionAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_overall_retention_amds/'.$cMContractOverallRetentionAmd->id
        );

        $this->assertApiResponse($cMContractOverallRetentionAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_c_m_contract_overall_retention_amd()
    {
        $cMContractOverallRetentionAmd = CMContractOverallRetentionAmd::factory()->create();
        $editedCMContractOverallRetentionAmd = CMContractOverallRetentionAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/c_m_contract_overall_retention_amds/'.$cMContractOverallRetentionAmd->id,
            $editedCMContractOverallRetentionAmd
        );

        $this->assertApiResponse($editedCMContractOverallRetentionAmd);
    }

    /**
     * @test
     */
    public function test_delete_c_m_contract_overall_retention_amd()
    {
        $cMContractOverallRetentionAmd = CMContractOverallRetentionAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/c_m_contract_overall_retention_amds/'.$cMContractOverallRetentionAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/c_m_contract_overall_retention_amds/'.$cMContractOverallRetentionAmd->id
        );

        $this->response->assertStatus(404);
    }
}
