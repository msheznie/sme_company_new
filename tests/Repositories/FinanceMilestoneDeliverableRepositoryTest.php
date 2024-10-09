<?php namespace Tests\Repositories;

use App\Models\FinanceMilestoneDeliverable;
use App\Repositories\FinanceMilestoneDeliverableRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class FinanceMilestoneDeliverableRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var FinanceMilestoneDeliverableRepository
     */
    protected $financeMilestoneDeliverableRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->financeMilestoneDeliverableRepo = \App::make(FinanceMilestoneDeliverableRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_finance_milestone_deliverable()
    {
        $financeMilestoneDeliverable = FinanceMilestoneDeliverable::factory()->make()->toArray();

        $createdFinanceMilestoneDeliverable = $this->financeMilestoneDeliverableRepo->create($financeMilestoneDeliverable);

        $createdFinanceMilestoneDeliverable = $createdFinanceMilestoneDeliverable->toArray();
        $this->assertArrayHasKey('id', $createdFinanceMilestoneDeliverable);
        $this->assertNotNull($createdFinanceMilestoneDeliverable['id'], 'Created FinanceMilestoneDeliverable must have id specified');
        $this->assertNotNull(FinanceMilestoneDeliverable::find($createdFinanceMilestoneDeliverable['id']), 'FinanceMilestoneDeliverable with given id must be in DB');
        $this->assertModelData($financeMilestoneDeliverable, $createdFinanceMilestoneDeliverable);
    }

    /**
     * @test read
     */
    public function test_read_finance_milestone_deliverable()
    {
        $financeMilestoneDeliverable = FinanceMilestoneDeliverable::factory()->create();

        $dbFinanceMilestoneDeliverable = $this->financeMilestoneDeliverableRepo->find($financeMilestoneDeliverable->id);

        $dbFinanceMilestoneDeliverable = $dbFinanceMilestoneDeliverable->toArray();
        $this->assertModelData($financeMilestoneDeliverable->toArray(), $dbFinanceMilestoneDeliverable);
    }

    /**
     * @test update
     */
    public function test_update_finance_milestone_deliverable()
    {
        $financeMilestoneDeliverable = FinanceMilestoneDeliverable::factory()->create();
        $fakeFinanceMilestoneDeliverable = FinanceMilestoneDeliverable::factory()->make()->toArray();

        $updatedFinanceMilestoneDeliverable = $this->financeMilestoneDeliverableRepo->update($fakeFinanceMilestoneDeliverable, $financeMilestoneDeliverable->id);

        $this->assertModelData($fakeFinanceMilestoneDeliverable, $updatedFinanceMilestoneDeliverable->toArray());
        $dbFinanceMilestoneDeliverable = $this->financeMilestoneDeliverableRepo->find($financeMilestoneDeliverable->id);
        $this->assertModelData($fakeFinanceMilestoneDeliverable, $dbFinanceMilestoneDeliverable->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_finance_milestone_deliverable()
    {
        $financeMilestoneDeliverable = FinanceMilestoneDeliverable::factory()->create();

        $resp = $this->financeMilestoneDeliverableRepo->delete($financeMilestoneDeliverable->id);

        $this->assertTrue($resp);
        $this->assertNull(FinanceMilestoneDeliverable::find($financeMilestoneDeliverable->id), 'FinanceMilestoneDeliverable should not exist in DB');
    }
}
