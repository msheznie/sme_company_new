<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\FinanceMilestoneDeliverable;

class FinanceMilestoneDeliverableApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_finance_milestone_deliverable()
    {
        $financeMilestoneDeliverable = FinanceMilestoneDeliverable::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/finance_milestone_deliverables', $financeMilestoneDeliverable
        );

        $this->assertApiResponse($financeMilestoneDeliverable);
    }

    /**
     * @test
     */
    public function test_read_finance_milestone_deliverable()
    {
        $financeMilestoneDeliverable = FinanceMilestoneDeliverable::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/finance_milestone_deliverables/'.$financeMilestoneDeliverable->id
        );

        $this->assertApiResponse($financeMilestoneDeliverable->toArray());
    }

    /**
     * @test
     */
    public function test_update_finance_milestone_deliverable()
    {
        $financeMilestoneDeliverable = FinanceMilestoneDeliverable::factory()->create();
        $editedFinanceMilestoneDeliverable = FinanceMilestoneDeliverable::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/finance_milestone_deliverables/'.$financeMilestoneDeliverable->id,
            $editedFinanceMilestoneDeliverable
        );

        $this->assertApiResponse($editedFinanceMilestoneDeliverable);
    }

    /**
     * @test
     */
    public function test_delete_finance_milestone_deliverable()
    {
        $financeMilestoneDeliverable = FinanceMilestoneDeliverable::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/finance_milestone_deliverables/'.$financeMilestoneDeliverable->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/finance_milestone_deliverables/'.$financeMilestoneDeliverable->id
        );

        $this->response->assertStatus(404);
    }
}
