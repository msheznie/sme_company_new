<?php namespace Tests\Repositories;

use App\Models\ContractMaster;
use App\Repositories\ContractMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractMasterRepository
     */
    protected $contractMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractMasterRepo = \App::make(ContractMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_master()
    {
        $contractMaster = ContractMaster::factory()->make()->toArray();

        $createdContractMaster = $this->contractMasterRepo->create($contractMaster);

        $createdContractMaster = $createdContractMaster->toArray();
        $this->assertArrayHasKey('id', $createdContractMaster);
        $this->assertNotNull($createdContractMaster['id'], 'Created ContractMaster must have id specified');
        $this->assertNotNull(ContractMaster::find($createdContractMaster['id']), 'ContractMaster with given id must be in DB');
        $this->assertModelData($contractMaster, $createdContractMaster);
    }

    /**
     * @test read
     */
    public function test_read_contract_master()
    {
        $contractMaster = ContractMaster::factory()->create();

        $dbContractMaster = $this->contractMasterRepo->find($contractMaster->id);

        $dbContractMaster = $dbContractMaster->toArray();
        $this->assertModelData($contractMaster->toArray(), $dbContractMaster);
    }

    /**
     * @test update
     */
    public function test_update_contract_master()
    {
        $contractMaster = ContractMaster::factory()->create();
        $fakeContractMaster = ContractMaster::factory()->make()->toArray();

        $updatedContractMaster = $this->contractMasterRepo->update($fakeContractMaster, $contractMaster->id);

        $this->assertModelData($fakeContractMaster, $updatedContractMaster->toArray());
        $dbContractMaster = $this->contractMasterRepo->find($contractMaster->id);
        $this->assertModelData($fakeContractMaster, $dbContractMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_master()
    {
        $contractMaster = ContractMaster::factory()->create();

        $resp = $this->contractMasterRepo->delete($contractMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractMaster::find($contractMaster->id), 'ContractMaster should not exist in DB');
    }
}
