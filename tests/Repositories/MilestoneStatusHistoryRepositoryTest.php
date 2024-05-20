<?php namespace Tests\Repositories;

use App\Models\MilestoneStatusHistory;
use App\Repositories\MilestoneStatusHistoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MilestoneStatusHistoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MilestoneStatusHistoryRepository
     */
    protected $milestoneStatusHistoryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->milestoneStatusHistoryRepo = \App::make(MilestoneStatusHistoryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_milestone_status_history()
    {
        $milestoneStatusHistory = MilestoneStatusHistory::factory()->make()->toArray();

        $createdMilestoneStatusHistory = $this->milestoneStatusHistoryRepo->create($milestoneStatusHistory);

        $createdMilestoneStatusHistory = $createdMilestoneStatusHistory->toArray();
        $this->assertArrayHasKey('id', $createdMilestoneStatusHistory);
        $this->assertNotNull($createdMilestoneStatusHistory['id'], 'Created MilestoneStatusHistory must have id specified');
        $this->assertNotNull(MilestoneStatusHistory::find($createdMilestoneStatusHistory['id']), 'MilestoneStatusHistory with given id must be in DB');
        $this->assertModelData($milestoneStatusHistory, $createdMilestoneStatusHistory);
    }

    /**
     * @test read
     */
    public function test_read_milestone_status_history()
    {
        $milestoneStatusHistory = MilestoneStatusHistory::factory()->create();

        $dbMilestoneStatusHistory = $this->milestoneStatusHistoryRepo->find($milestoneStatusHistory->id);

        $dbMilestoneStatusHistory = $dbMilestoneStatusHistory->toArray();
        $this->assertModelData($milestoneStatusHistory->toArray(), $dbMilestoneStatusHistory);
    }

    /**
     * @test update
     */
    public function test_update_milestone_status_history()
    {
        $milestoneStatusHistory = MilestoneStatusHistory::factory()->create();
        $fakeMilestoneStatusHistory = MilestoneStatusHistory::factory()->make()->toArray();

        $updatedMilestoneStatusHistory = $this->milestoneStatusHistoryRepo->update($fakeMilestoneStatusHistory, $milestoneStatusHistory->id);

        $this->assertModelData($fakeMilestoneStatusHistory, $updatedMilestoneStatusHistory->toArray());
        $dbMilestoneStatusHistory = $this->milestoneStatusHistoryRepo->find($milestoneStatusHistory->id);
        $this->assertModelData($fakeMilestoneStatusHistory, $dbMilestoneStatusHistory->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_milestone_status_history()
    {
        $milestoneStatusHistory = MilestoneStatusHistory::factory()->create();

        $resp = $this->milestoneStatusHistoryRepo->delete($milestoneStatusHistory->id);

        $this->assertTrue($resp);
        $this->assertNull(MilestoneStatusHistory::find($milestoneStatusHistory->id), 'MilestoneStatusHistory should not exist in DB');
    }
}
