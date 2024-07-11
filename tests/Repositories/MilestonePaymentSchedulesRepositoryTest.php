<?php namespace Tests\Repositories;

use App\Models\MilestonePaymentSchedules;
use App\Repositories\MilestonePaymentSchedulesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MilestonePaymentSchedulesRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MilestonePaymentSchedulesRepository
     */
    protected $milestonePaymentSchedulesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->milestonePaymentSchedulesRepo = \App::make(MilestonePaymentSchedulesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_milestone_payment_schedules()
    {
        $milestonePaymentSchedules = MilestonePaymentSchedules::factory()->make()->toArray();

        $createdMilestonePaymentSchedules = $this->milestonePaymentSchedulesRepo->create($milestonePaymentSchedules);

        $createdMilestonePaymentSchedules = $createdMilestonePaymentSchedules->toArray();
        $this->assertArrayHasKey('id', $createdMilestonePaymentSchedules);
        $this->assertNotNull($createdMilestonePaymentSchedules['id'],
            'Created MilestonePaymentSchedules must have id specified');
        $this->assertNotNull(MilestonePaymentSchedules::find($createdMilestonePaymentSchedules['id']),
            'MilestonePaymentSchedules with given id must be in DB');
        $this->assertModelData($milestonePaymentSchedules, $createdMilestonePaymentSchedules);
    }

    /**
     * @test read
     */
    public function test_read_milestone_payment_schedules()
    {
        $milestonePaymentSchedules = MilestonePaymentSchedules::factory()->create();

        $dbMilestonePaymentSchedules = $this->milestonePaymentSchedulesRepo->find($milestonePaymentSchedules->id);

        $dbMilestonePaymentSchedules = $dbMilestonePaymentSchedules->toArray();
        $this->assertModelData($milestonePaymentSchedules->toArray(), $dbMilestonePaymentSchedules);
    }

    /**
     * @test update
     */
    public function test_update_milestone_payment_schedules()
    {
        $milestonePaymentSchedules = MilestonePaymentSchedules::factory()->create();
        $fakeMilestonePaymentSchedules = MilestonePaymentSchedules::factory()->make()->toArray();

        $updatedMilestonePaymentSchedules = $this->milestonePaymentSchedulesRepo->update(
            $fakeMilestonePaymentSchedules, $milestonePaymentSchedules->id);

        $this->assertModelData($fakeMilestonePaymentSchedules, $updatedMilestonePaymentSchedules->toArray());
        $dbMilestonePaymentSchedules = $this->milestonePaymentSchedulesRepo->find($milestonePaymentSchedules->id);
        $this->assertModelData($fakeMilestonePaymentSchedules, $dbMilestonePaymentSchedules->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_milestone_payment_schedules()
    {
        $milestonePaymentSchedules = MilestonePaymentSchedules::factory()->create();

        $resp = $this->milestonePaymentSchedulesRepo->delete($milestonePaymentSchedules->id);

        $this->assertTrue($resp);
        $this->assertNull(MilestonePaymentSchedules::find($milestonePaymentSchedules->id),
            'MilestonePaymentSchedules should not exist in DB');
    }
}
