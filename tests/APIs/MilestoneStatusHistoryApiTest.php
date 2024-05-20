<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MilestoneStatusHistory;

class MilestoneStatusHistoryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_milestone_status_history()
    {
        $milestoneStatusHistory = MilestoneStatusHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/milestone_status_histories', $milestoneStatusHistory
        );

        $this->assertApiResponse($milestoneStatusHistory);
    }

    /**
     * @test
     */
    public function test_read_milestone_status_history()
    {
        $milestoneStatusHistory = MilestoneStatusHistory::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/milestone_status_histories/'.$milestoneStatusHistory->id
        );

        $this->assertApiResponse($milestoneStatusHistory->toArray());
    }

    /**
     * @test
     */
    public function test_update_milestone_status_history()
    {
        $milestoneStatusHistory = MilestoneStatusHistory::factory()->create();
        $editedMilestoneStatusHistory = MilestoneStatusHistory::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/milestone_status_histories/'.$milestoneStatusHistory->id,
            $editedMilestoneStatusHistory
        );

        $this->assertApiResponse($editedMilestoneStatusHistory);
    }

    /**
     * @test
     */
    public function test_delete_milestone_status_history()
    {
        $milestoneStatusHistory = MilestoneStatusHistory::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/milestone_status_histories/'.$milestoneStatusHistory->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/milestone_status_histories/'.$milestoneStatusHistory->id
        );

        $this->response->assertStatus(404);
    }
}
