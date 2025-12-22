<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractMilestone;

class ContractMilestoneApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_milestone()
    {
        $contractMilestone = ContractMilestone::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_milestones', $contractMilestone
        );

        $this->assertApiResponse($contractMilestone);
    }

    /**
     * @test
     */
    public function test_read_contract_milestone()
    {
        $contractMilestone = ContractMilestone::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_milestones/'.$contractMilestone->id
        );

        $this->assertApiResponse($contractMilestone->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_milestone()
    {
        $contractMilestone = ContractMilestone::factory()->create();
        $editedContractMilestone = ContractMilestone::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_milestones/'.$contractMilestone->id,
            $editedContractMilestone
        );

        $this->assertApiResponse($editedContractMilestone);
    }

    /**
     * @test
     */
    public function test_delete_contract_milestone()
    {
        $contractMilestone = ContractMilestone::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_milestones/'.$contractMilestone->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_milestones/'.$contractMilestone->id
        );

        $this->response->assertStatus(404);
    }
}
