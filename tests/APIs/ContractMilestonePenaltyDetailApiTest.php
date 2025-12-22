<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractMilestonePenaltyDetail;

class ContractMilestonePenaltyDetailApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_milestone_penalty_detail()
    {
        $contractMilestonePenaltyDetail = ContractMilestonePenaltyDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_milestone_penalty_details', $contractMilestonePenaltyDetail
        );

        $this->assertApiResponse($contractMilestonePenaltyDetail);
    }

    /**
     * @test
     */
    public function test_read_contract_milestone_penalty_detail()
    {
        $contractMilestonePenaltyDetail = ContractMilestonePenaltyDetail::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_milestone_penalty_details/'.$contractMilestonePenaltyDetail->id
        );

        $this->assertApiResponse($contractMilestonePenaltyDetail->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_milestone_penalty_detail()
    {
        $contractMilestonePenaltyDetail = ContractMilestonePenaltyDetail::factory()->create();
        $editedContractMilestonePenaltyDetail = ContractMilestonePenaltyDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_milestone_penalty_details/'.$contractMilestonePenaltyDetail->id,
            $editedContractMilestonePenaltyDetail
        );

        $this->assertApiResponse($editedContractMilestonePenaltyDetail);
    }

    /**
     * @test
     */
    public function test_delete_contract_milestone_penalty_detail()
    {
        $contractMilestonePenaltyDetail = ContractMilestonePenaltyDetail::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_milestone_penalty_details/'.$contractMilestonePenaltyDetail->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_milestone_penalty_details/'.$contractMilestonePenaltyDetail->id
        );

        $this->response->assertStatus(404);
    }
}
