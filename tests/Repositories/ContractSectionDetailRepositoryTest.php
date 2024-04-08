<?php namespace Tests\Repositories;

use App\Models\ContractSectionDetail;
use App\Repositories\ContractSectionDetailRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractSectionDetailRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractSectionDetailRepository
     */
    protected $contractSectionDetailRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractSectionDetailRepo = \App::make(ContractSectionDetailRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_section_detail()
    {
        $contractSectionDetail = ContractSectionDetail::factory()->make()->toArray();

        $createdContractSectionDetail = $this->contractSectionDetailRepo->create($contractSectionDetail);

        $createdContractSectionDetail = $createdContractSectionDetail->toArray();
        $this->assertArrayHasKey('id', $createdContractSectionDetail);
        $this->assertNotNull($createdContractSectionDetail['id'], 'Created ContractSectionDetail must have id specified');
        $this->assertNotNull(ContractSectionDetail::find($createdContractSectionDetail['id']), 'ContractSectionDetail with given id must be in DB');
        $this->assertModelData($contractSectionDetail, $createdContractSectionDetail);
    }

    /**
     * @test read
     */
    public function test_read_contract_section_detail()
    {
        $contractSectionDetail = ContractSectionDetail::factory()->create();

        $dbContractSectionDetail = $this->contractSectionDetailRepo->find($contractSectionDetail->id);

        $dbContractSectionDetail = $dbContractSectionDetail->toArray();
        $this->assertModelData($contractSectionDetail->toArray(), $dbContractSectionDetail);
    }

    /**
     * @test update
     */
    public function test_update_contract_section_detail()
    {
        $contractSectionDetail = ContractSectionDetail::factory()->create();
        $fakeContractSectionDetail = ContractSectionDetail::factory()->make()->toArray();

        $updatedContractSectionDetail = $this->contractSectionDetailRepo->update($fakeContractSectionDetail, $contractSectionDetail->id);

        $this->assertModelData($fakeContractSectionDetail, $updatedContractSectionDetail->toArray());
        $dbContractSectionDetail = $this->contractSectionDetailRepo->find($contractSectionDetail->id);
        $this->assertModelData($fakeContractSectionDetail, $dbContractSectionDetail->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_section_detail()
    {
        $contractSectionDetail = ContractSectionDetail::factory()->create();

        $resp = $this->contractSectionDetailRepo->delete($contractSectionDetail->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractSectionDetail::find($contractSectionDetail->id), 'ContractSectionDetail should not exist in DB');
    }
}
