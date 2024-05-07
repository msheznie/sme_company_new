<?php namespace Tests\Repositories;

use App\Models\ContractMilestoneRetention;
use App\Repositories\ContractMilestoneRetentionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractMilestoneRetentionRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractMilestoneRetentionRepository
     */
    protected $contractMilestoneRetentionRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractMilestoneRetentionRepo = \App::make(ContractMilestoneRetentionRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_milestone_retention()
    {
        $contractMilestoneRetention = ContractMilestoneRetention::factory()->make()->toArray();

        $createdContractMilestoneRetention = $this->contractMilestoneRetentionRepo->create($contractMilestoneRetention);

        $createdContractMilestoneRetention = $createdContractMilestoneRetention->toArray();
        $this->assertArrayHasKey('id', $createdContractMilestoneRetention);
        $this->assertNotNull($createdContractMilestoneRetention['id'], 'Created ContractMilestoneRetention must have id specified');
        $this->assertNotNull(ContractMilestoneRetention::find($createdContractMilestoneRetention['id']), 'ContractMilestoneRetention with given id must be in DB');
        $this->assertModelData($contractMilestoneRetention, $createdContractMilestoneRetention);
    }

    /**
     * @test read
     */
    public function test_read_contract_milestone_retention()
    {
        $contractMilestoneRetention = ContractMilestoneRetention::factory()->create();

        $dbContractMilestoneRetention = $this->contractMilestoneRetentionRepo->find($contractMilestoneRetention->id);

        $dbContractMilestoneRetention = $dbContractMilestoneRetention->toArray();
        $this->assertModelData($contractMilestoneRetention->toArray(), $dbContractMilestoneRetention);
    }

    /**
     * @test update
     */
    public function test_update_contract_milestone_retention()
    {
        $contractMilestoneRetention = ContractMilestoneRetention::factory()->create();
        $fakeContractMilestoneRetention = ContractMilestoneRetention::factory()->make()->toArray();

        $updatedContractMilestoneRetention = $this->contractMilestoneRetentionRepo->update($fakeContractMilestoneRetention, $contractMilestoneRetention->id);

        $this->assertModelData($fakeContractMilestoneRetention, $updatedContractMilestoneRetention->toArray());
        $dbContractMilestoneRetention = $this->contractMilestoneRetentionRepo->find($contractMilestoneRetention->id);
        $this->assertModelData($fakeContractMilestoneRetention, $dbContractMilestoneRetention->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_milestone_retention()
    {
        $contractMilestoneRetention = ContractMilestoneRetention::factory()->create();

        $resp = $this->contractMilestoneRetentionRepo->delete($contractMilestoneRetention->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractMilestoneRetention::find($contractMilestoneRetention->id), 'ContractMilestoneRetention should not exist in DB');
    }
}
