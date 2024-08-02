<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractMilestonePenaltyMaster;

class ContractMilestonePenaltyMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_milestone_penalty_master()
    {
        $contractMilestonePenaltyMaster = ContractMilestonePenaltyMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_milestone_penalty_masters', $contractMilestonePenaltyMaster
        );

        $this->assertApiResponse($contractMilestonePenaltyMaster);
    }

    /**
     * @test
     */
    public function test_read_contract_milestone_penalty_master()
    {
        $contractMilestonePenaltyMaster = ContractMilestonePenaltyMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_milestone_penalty_masters/'.$contractMilestonePenaltyMaster->id
        );

        $this->assertApiResponse($contractMilestonePenaltyMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_milestone_penalty_master()
    {
        $contractMilestonePenaltyMaster = ContractMilestonePenaltyMaster::factory()->create();
        $editedContractMilestonePenaltyMaster = ContractMilestonePenaltyMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_milestone_penalty_masters/'.$contractMilestonePenaltyMaster->id,
            $editedContractMilestonePenaltyMaster
        );

        $this->assertApiResponse($editedContractMilestonePenaltyMaster);
    }

    /**
     * @test
     */
    public function test_delete_contract_milestone_penalty_master()
    {
        $contractMilestonePenaltyMaster = ContractMilestonePenaltyMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_milestone_penalty_masters/'.$contractMilestonePenaltyMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_milestone_penalty_masters/'.$contractMilestonePenaltyMaster->id
        );

        $this->response->assertStatus(404);
    }
}
