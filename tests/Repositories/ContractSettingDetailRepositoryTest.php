<?php namespace Tests\Repositories;

use App\Models\ContractSettingDetail;
use App\Repositories\ContractSettingDetailRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractSettingDetailRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractSettingDetailRepository
     */
    protected $contractSettingDetailRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractSettingDetailRepo = \App::make(ContractSettingDetailRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_setting_detail()
    {
        $contractSettingDetail = ContractSettingDetail::factory()->make()->toArray();

        $createdContractSettingDetail = $this->contractSettingDetailRepo->create($contractSettingDetail);

        $createdContractSettingDetail = $createdContractSettingDetail->toArray();
        $this->assertArrayHasKey('id', $createdContractSettingDetail);
        $this->assertNotNull($createdContractSettingDetail['id'], 'Created ContractSettingDetail must have id specified');
        $this->assertNotNull(ContractSettingDetail::find($createdContractSettingDetail['id']), 'ContractSettingDetail with given id must be in DB');
        $this->assertModelData($contractSettingDetail, $createdContractSettingDetail);
    }

    /**
     * @test read
     */
    public function test_read_contract_setting_detail()
    {
        $contractSettingDetail = ContractSettingDetail::factory()->create();

        $dbContractSettingDetail = $this->contractSettingDetailRepo->find($contractSettingDetail->id);

        $dbContractSettingDetail = $dbContractSettingDetail->toArray();
        $this->assertModelData($contractSettingDetail->toArray(), $dbContractSettingDetail);
    }

    /**
     * @test update
     */
    public function test_update_contract_setting_detail()
    {
        $contractSettingDetail = ContractSettingDetail::factory()->create();
        $fakeContractSettingDetail = ContractSettingDetail::factory()->make()->toArray();

        $updatedContractSettingDetail = $this->contractSettingDetailRepo->update($fakeContractSettingDetail, $contractSettingDetail->id);

        $this->assertModelData($fakeContractSettingDetail, $updatedContractSettingDetail->toArray());
        $dbContractSettingDetail = $this->contractSettingDetailRepo->find($contractSettingDetail->id);
        $this->assertModelData($fakeContractSettingDetail, $dbContractSettingDetail->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_setting_detail()
    {
        $contractSettingDetail = ContractSettingDetail::factory()->create();

        $resp = $this->contractSettingDetailRepo->delete($contractSettingDetail->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractSettingDetail::find($contractSettingDetail->id), 'ContractSettingDetail should not exist in DB');
    }
}
