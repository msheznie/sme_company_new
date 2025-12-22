<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractOverallPenaltyAmd;

class ContractOverallPenaltyAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_overall_penalty_amd()
    {
        $contractOverallPenaltyAmd = ContractOverallPenaltyAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_overall_penalty_amds', $contractOverallPenaltyAmd
        );

        $this->assertApiResponse($contractOverallPenaltyAmd);
    }

    /**
     * @test
     */
    public function test_read_contract_overall_penalty_amd()
    {
        $contractOverallPenaltyAmd = ContractOverallPenaltyAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_overall_penalty_amds/'.$contractOverallPenaltyAmd->id
        );

        $this->assertApiResponse($contractOverallPenaltyAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_overall_penalty_amd()
    {
        $contractOverallPenaltyAmd = ContractOverallPenaltyAmd::factory()->create();
        $editedContractOverallPenaltyAmd = ContractOverallPenaltyAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_overall_penalty_amds/'.$contractOverallPenaltyAmd->id,
            $editedContractOverallPenaltyAmd
        );

        $this->assertApiResponse($editedContractOverallPenaltyAmd);
    }

    /**
     * @test
     */
    public function test_delete_contract_overall_penalty_amd()
    {
        $contractOverallPenaltyAmd = ContractOverallPenaltyAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_overall_penalty_amds/'.$contractOverallPenaltyAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_overall_penalty_amds/'.$contractOverallPenaltyAmd->id
        );

        $this->response->assertStatus(404);
    }
}
