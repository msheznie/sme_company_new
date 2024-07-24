<?php namespace Tests\Repositories;

use App\Models\ContractMilestone;
use App\Repositories\ContractMilestoneRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractMilestoneRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractMilestoneRepository
     */
    protected $contractMilestoneRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractMilestoneRepo = \App::make(ContractMilestoneRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_milestone()
    {
        $contractMilestone = ContractMilestone::factory()->make()->toArray();

        $createdContractMilestone = $this->contractMilestoneRepo->create($contractMilestone);

        $createdContractMilestone = $createdContractMilestone->toArray();
        $this->assertArrayHasKey('id', $createdContractMilestone);
        $this->assertNotNull($createdContractMilestone['id'], 'Created ContractMilestone must have id specified');
        $this->assertNotNull(ContractMilestone::find($createdContractMilestone['id']), 'ContractMilestone with given id must be in DB');
        $this->assertModelData($contractMilestone, $createdContractMilestone);
    }

    /**
     * @test read
     */
    public function test_read_contract_milestone()
    {
        $contractMilestone = ContractMilestone::factory()->create();

        $dbContractMilestone = $this->contractMilestoneRepo->find($contractMilestone->id);

        $dbContractMilestone = $dbContractMilestone->toArray();
        $this->assertModelData($contractMilestone->toArray(), $dbContractMilestone);
    }

    /**
     * @test update
     */
    public function test_update_contract_milestone()
    {
        $contractMilestone = ContractMilestone::factory()->create();
        $fakeContractMilestone = ContractMilestone::factory()->make()->toArray();

        $updatedContractMilestone = $this->contractMilestoneRepo->update($fakeContractMilestone, $contractMilestone->id);

        $this->assertModelData($fakeContractMilestone, $updatedContractMilestone->toArray());
        $dbContractMilestone = $this->contractMilestoneRepo->find($contractMilestone->id);
        $this->assertModelData($fakeContractMilestone, $dbContractMilestone->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_milestone()
    {
        $contractMilestone = ContractMilestone::factory()->create();

        $resp = $this->contractMilestoneRepo->delete($contractMilestone->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractMilestone::find($contractMilestone->id), 'ContractMilestone should not exist in DB');
    }
}
