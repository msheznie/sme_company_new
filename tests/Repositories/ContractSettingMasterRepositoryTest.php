<?php namespace Tests\Repositories;

use App\Models\ContractSettingMaster;
use App\Repositories\ContractSettingMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractSettingMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractSettingMasterRepository
     */
    protected $contractSettingMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractSettingMasterRepo = \App::make(ContractSettingMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_setting_master()
    {
        $contractSettingMaster = ContractSettingMaster::factory()->make()->toArray();

        $createdContractSettingMaster = $this->contractSettingMasterRepo->create($contractSettingMaster);

        $createdContractSettingMaster = $createdContractSettingMaster->toArray();
        $this->assertArrayHasKey('id', $createdContractSettingMaster);
        $this->assertNotNull($createdContractSettingMaster['id'], 'Created ContractSettingMaster must have id specified');
        $this->assertNotNull(ContractSettingMaster::find($createdContractSettingMaster['id']), 'ContractSettingMaster with given id must be in DB');
        $this->assertModelData($contractSettingMaster, $createdContractSettingMaster);
    }

    /**
     * @test read
     */
    public function test_read_contract_setting_master()
    {
        $contractSettingMaster = ContractSettingMaster::factory()->create();

        $dbContractSettingMaster = $this->contractSettingMasterRepo->find($contractSettingMaster->id);

        $dbContractSettingMaster = $dbContractSettingMaster->toArray();
        $this->assertModelData($contractSettingMaster->toArray(), $dbContractSettingMaster);
    }

    /**
     * @test update
     */
    public function test_update_contract_setting_master()
    {
        $contractSettingMaster = ContractSettingMaster::factory()->create();
        $fakeContractSettingMaster = ContractSettingMaster::factory()->make()->toArray();

        $updatedContractSettingMaster = $this->contractSettingMasterRepo->update($fakeContractSettingMaster, $contractSettingMaster->id);

        $this->assertModelData($fakeContractSettingMaster, $updatedContractSettingMaster->toArray());
        $dbContractSettingMaster = $this->contractSettingMasterRepo->find($contractSettingMaster->id);
        $this->assertModelData($fakeContractSettingMaster, $dbContractSettingMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_setting_master()
    {
        $contractSettingMaster = ContractSettingMaster::factory()->create();

        $resp = $this->contractSettingMasterRepo->delete($contractSettingMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractSettingMaster::find($contractSettingMaster->id), 'ContractSettingMaster should not exist in DB');
    }
}
