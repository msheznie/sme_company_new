<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractMilestoneRetention;

class ContractMilestoneRetentionApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_milestone_retention()
    {
        $contractMilestoneRetention = ContractMilestoneRetention::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_milestone_retentions', $contractMilestoneRetention
        );

        $this->assertApiResponse($contractMilestoneRetention);
    }

    /**
     * @test
     */
    public function test_read_contract_milestone_retention()
    {
        $contractMilestoneRetention = ContractMilestoneRetention::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_milestone_retentions/'.$contractMilestoneRetention->id
        );

        $this->assertApiResponse($contractMilestoneRetention->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_milestone_retention()
    {
        $contractMilestoneRetention = ContractMilestoneRetention::factory()->create();
        $editedContractMilestoneRetention = ContractMilestoneRetention::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_milestone_retentions/'.$contractMilestoneRetention->id,
            $editedContractMilestoneRetention
        );

        $this->assertApiResponse($editedContractMilestoneRetention);
    }

    /**
     * @test
     */
    public function test_delete_contract_milestone_retention()
    {
        $contractMilestoneRetention = ContractMilestoneRetention::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_milestone_retentions/'.$contractMilestoneRetention->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_milestone_retentions/'.$contractMilestoneRetention->id
        );

        $this->response->assertStatus(404);
    }
}
