<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractOverallPenalty;

class ContractOverallPenaltyApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_overall_penalty()
    {
        $contractOverallPenalty = ContractOverallPenalty::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_overall_penalties', $contractOverallPenalty
        );

        $this->assertApiResponse($contractOverallPenalty);
    }

    /**
     * @test
     */
    public function test_read_contract_overall_penalty()
    {
        $contractOverallPenalty = ContractOverallPenalty::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_overall_penalties/'.$contractOverallPenalty->id
        );

        $this->assertApiResponse($contractOverallPenalty->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_overall_penalty()
    {
        $contractOverallPenalty = ContractOverallPenalty::factory()->create();
        $editedContractOverallPenalty = ContractOverallPenalty::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_overall_penalties/'.$contractOverallPenalty->id,
            $editedContractOverallPenalty
        );

        $this->assertApiResponse($editedContractOverallPenalty);
    }

    /**
     * @test
     */
    public function test_delete_contract_overall_penalty()
    {
        $contractOverallPenalty = ContractOverallPenalty::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_overall_penalties/'.$contractOverallPenalty->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_overall_penalties/'.$contractOverallPenalty->id
        );

        $this->response->assertStatus(404);
    }
}
