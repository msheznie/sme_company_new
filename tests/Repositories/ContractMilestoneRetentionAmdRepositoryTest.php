<?php namespace Tests\Repositories;

use App\Models\ContractMilestoneRetentionAmd;
use App\Repositories\ContractMilestoneRetentionAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractMilestoneRetentionAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractMilestoneRetentionAmdRepository
     */
    protected $contractMilestoneRetentionAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractMilestoneRetentionAmdRepo = \App::make(ContractMilestoneRetentionAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_milestone_retention_amd()
    {
        $contractMilestoneRetentionAmd = ContractMilestoneRetentionAmd::factory()->make()->toArray();

        $createdContractMilestoneRetentionAmd = $this->contractMilestoneRetentionAmdRepo->create($contractMilestoneRetentionAmd);

        $createdContractMilestoneRetentionAmd = $createdContractMilestoneRetentionAmd->toArray();
        $this->assertArrayHasKey('id', $createdContractMilestoneRetentionAmd);
        $this->assertNotNull($createdContractMilestoneRetentionAmd['id'], 'Created ContractMilestoneRetentionAmd must have id specified');
        $this->assertNotNull(ContractMilestoneRetentionAmd::find($createdContractMilestoneRetentionAmd['id']), 'ContractMilestoneRetentionAmd with given id must be in DB');
        $this->assertModelData($contractMilestoneRetentionAmd, $createdContractMilestoneRetentionAmd);
    }

    /**
     * @test read
     */
    public function test_read_contract_milestone_retention_amd()
    {
        $contractMilestoneRetentionAmd = ContractMilestoneRetentionAmd::factory()->create();

        $dbContractMilestoneRetentionAmd = $this->contractMilestoneRetentionAmdRepo->find($contractMilestoneRetentionAmd->id);

        $dbContractMilestoneRetentionAmd = $dbContractMilestoneRetentionAmd->toArray();
        $this->assertModelData($contractMilestoneRetentionAmd->toArray(), $dbContractMilestoneRetentionAmd);
    }

    /**
     * @test update
     */
    public function test_update_contract_milestone_retention_amd()
    {
        $contractMilestoneRetentionAmd = ContractMilestoneRetentionAmd::factory()->create();
        $fakeContractMilestoneRetentionAmd = ContractMilestoneRetentionAmd::factory()->make()->toArray();

        $updatedContractMilestoneRetentionAmd = $this->contractMilestoneRetentionAmdRepo->update($fakeContractMilestoneRetentionAmd, $contractMilestoneRetentionAmd->id);

        $this->assertModelData($fakeContractMilestoneRetentionAmd, $updatedContractMilestoneRetentionAmd->toArray());
        $dbContractMilestoneRetentionAmd = $this->contractMilestoneRetentionAmdRepo->find($contractMilestoneRetentionAmd->id);
        $this->assertModelData($fakeContractMilestoneRetentionAmd, $dbContractMilestoneRetentionAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_milestone_retention_amd()
    {
        $contractMilestoneRetentionAmd = ContractMilestoneRetentionAmd::factory()->create();

        $resp = $this->contractMilestoneRetentionAmdRepo->delete($contractMilestoneRetentionAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractMilestoneRetentionAmd::find($contractMilestoneRetentionAmd->id), 'ContractMilestoneRetentionAmd should not exist in DB');
    }
}
