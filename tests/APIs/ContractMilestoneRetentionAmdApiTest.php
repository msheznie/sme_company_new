<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractMilestoneRetentionAmd;

class ContractMilestoneRetentionAmdApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_milestone_retention_amd()
    {
        $contractMilestoneRetentionAmd = ContractMilestoneRetentionAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_milestone_retention_amds', $contractMilestoneRetentionAmd
        );

        $this->assertApiResponse($contractMilestoneRetentionAmd);
    }

    /**
     * @test
     */
    public function test_read_contract_milestone_retention_amd()
    {
        $contractMilestoneRetentionAmd = ContractMilestoneRetentionAmd::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_milestone_retention_amds/'.$contractMilestoneRetentionAmd->id
        );

        $this->assertApiResponse($contractMilestoneRetentionAmd->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_milestone_retention_amd()
    {
        $contractMilestoneRetentionAmd = ContractMilestoneRetentionAmd::factory()->create();
        $editedContractMilestoneRetentionAmd = ContractMilestoneRetentionAmd::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_milestone_retention_amds/'.$contractMilestoneRetentionAmd->id,
            $editedContractMilestoneRetentionAmd
        );

        $this->assertApiResponse($editedContractMilestoneRetentionAmd);
    }

    /**
     * @test
     */
    public function test_delete_contract_milestone_retention_amd()
    {
        $contractMilestoneRetentionAmd = ContractMilestoneRetentionAmd::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_milestone_retention_amds/'.$contractMilestoneRetentionAmd->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_milestone_retention_amds/'.$contractMilestoneRetentionAmd->id
        );

        $this->response->assertStatus(404);
    }
}
