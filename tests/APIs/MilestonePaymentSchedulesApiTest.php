<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MilestonePaymentSchedules;

class MilestonePaymentSchedulesApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;
    protected $url = '/api/milestone_payment_schedules/';
    /**
     * @test
     */
    public function test_create_milestone_payment_schedules()
    {
        $milestonePaymentSchedules = MilestonePaymentSchedules::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/milestone_payment_schedules', $milestonePaymentSchedules
        );

        $this->assertApiResponse($milestonePaymentSchedules);
    }

    /**
     * @test
     */
    public function test_read_milestone_payment_schedules()
    {
        $milestonePaymentSchedules = MilestonePaymentSchedules::factory()->create();

        $this->response = $this->json(
            'GET',
            $this->url.$milestonePaymentSchedules->id
        );

        $this->assertApiResponse($milestonePaymentSchedules->toArray());
    }

    /**
     * @test
     */
    public function test_update_milestone_payment_schedules()
    {
        $milestonePaymentSchedules = MilestonePaymentSchedules::factory()->create();
        $editedMilestonePaymentSchedules = MilestonePaymentSchedules::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            $this->url.$milestonePaymentSchedules->id,
            $editedMilestonePaymentSchedules
        );

        $this->assertApiResponse($editedMilestonePaymentSchedules);
    }

    /**
     * @test
     */
    public function test_delete_milestone_payment_schedules()
    {
        $milestonePaymentSchedules = MilestonePaymentSchedules::factory()->create();

        $this->response = $this->json(
            'DELETE',
            $this->url.$milestonePaymentSchedules->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            $this->url.$milestonePaymentSchedules->id
        );

        $this->response->assertStatus(404);
    }
}
