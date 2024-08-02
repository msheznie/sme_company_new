<?php namespace Tests\Repositories;

use App\Models\ContractMilestonePenaltyMaster;
use App\Repositories\ContractMilestonePenaltyMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractMilestonePenaltyMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractMilestonePenaltyMasterRepository
     */
    protected $contractMilestonePenaltyMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractMilestonePenaltyMasterRepo = \App::make(ContractMilestonePenaltyMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_milestone_penalty_master()
    {
        $contractMilestonePenaltyMaster = ContractMilestonePenaltyMaster::factory()->make()->toArray();

        $createdContractMilestonePenaltyMaster = $this->contractMilestonePenaltyMasterRepo->create($contractMilestonePenaltyMaster);

        $createdContractMilestonePenaltyMaster = $createdContractMilestonePenaltyMaster->toArray();
        $this->assertArrayHasKey('id', $createdContractMilestonePenaltyMaster);
        $this->assertNotNull($createdContractMilestonePenaltyMaster['id'], 'Created ContractMilestonePenaltyMaster must have id specified');
        $this->assertNotNull(ContractMilestonePenaltyMaster::find($createdContractMilestonePenaltyMaster['id']), 'ContractMilestonePenaltyMaster with given id must be in DB');
        $this->assertModelData($contractMilestonePenaltyMaster, $createdContractMilestonePenaltyMaster);
    }

    /**
     * @test read
     */
    public function test_read_contract_milestone_penalty_master()
    {
        $contractMilestonePenaltyMaster = ContractMilestonePenaltyMaster::factory()->create();

        $dbContractMilestonePenaltyMaster = $this->contractMilestonePenaltyMasterRepo->find($contractMilestonePenaltyMaster->id);

        $dbContractMilestonePenaltyMaster = $dbContractMilestonePenaltyMaster->toArray();
        $this->assertModelData($contractMilestonePenaltyMaster->toArray(), $dbContractMilestonePenaltyMaster);
    }

    /**
     * @test update
     */
    public function test_update_contract_milestone_penalty_master()
    {
        $contractMilestonePenaltyMaster = ContractMilestonePenaltyMaster::factory()->create();
        $fakeContractMilestonePenaltyMaster = ContractMilestonePenaltyMaster::factory()->make()->toArray();

        $updatedContractMilestonePenaltyMaster = $this->contractMilestonePenaltyMasterRepo->update($fakeContractMilestonePenaltyMaster, $contractMilestonePenaltyMaster->id);

        $this->assertModelData($fakeContractMilestonePenaltyMaster, $updatedContractMilestonePenaltyMaster->toArray());
        $dbContractMilestonePenaltyMaster = $this->contractMilestonePenaltyMasterRepo->find($contractMilestonePenaltyMaster->id);
        $this->assertModelData($fakeContractMilestonePenaltyMaster, $dbContractMilestonePenaltyMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_milestone_penalty_master()
    {
        $contractMilestonePenaltyMaster = ContractMilestonePenaltyMaster::factory()->create();

        $resp = $this->contractMilestonePenaltyMasterRepo->delete($contractMilestonePenaltyMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractMilestonePenaltyMaster::find($contractMilestonePenaltyMaster->id), 'ContractMilestonePenaltyMaster should not exist in DB');
    }
}
