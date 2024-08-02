<?php namespace Tests\Repositories;

use App\Models\ContractMilestonePenaltyDetail;
use App\Repositories\ContractMilestonePenaltyDetailRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractMilestonePenaltyDetailRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractMilestonePenaltyDetailRepository
     */
    protected $contractMilestonePenaltyDetailRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractMilestonePenaltyDetailRepo = \App::make(ContractMilestonePenaltyDetailRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_milestone_penalty_detail()
    {
        $contractMilestonePenaltyDetail = ContractMilestonePenaltyDetail::factory()->make()->toArray();

        $createdContractMilestonePenaltyDetail = $this->contractMilestonePenaltyDetailRepo->create($contractMilestonePenaltyDetail);

        $createdContractMilestonePenaltyDetail = $createdContractMilestonePenaltyDetail->toArray();
        $this->assertArrayHasKey('id', $createdContractMilestonePenaltyDetail);
        $this->assertNotNull($createdContractMilestonePenaltyDetail['id'], 'Created ContractMilestonePenaltyDetail must have id specified');
        $this->assertNotNull(ContractMilestonePenaltyDetail::find($createdContractMilestonePenaltyDetail['id']), 'ContractMilestonePenaltyDetail with given id must be in DB');
        $this->assertModelData($contractMilestonePenaltyDetail, $createdContractMilestonePenaltyDetail);
    }

    /**
     * @test read
     */
    public function test_read_contract_milestone_penalty_detail()
    {
        $contractMilestonePenaltyDetail = ContractMilestonePenaltyDetail::factory()->create();

        $dbContractMilestonePenaltyDetail = $this->contractMilestonePenaltyDetailRepo->find($contractMilestonePenaltyDetail->id);

        $dbContractMilestonePenaltyDetail = $dbContractMilestonePenaltyDetail->toArray();
        $this->assertModelData($contractMilestonePenaltyDetail->toArray(), $dbContractMilestonePenaltyDetail);
    }

    /**
     * @test update
     */
    public function test_update_contract_milestone_penalty_detail()
    {
        $contractMilestonePenaltyDetail = ContractMilestonePenaltyDetail::factory()->create();
        $fakeContractMilestonePenaltyDetail = ContractMilestonePenaltyDetail::factory()->make()->toArray();

        $updatedContractMilestonePenaltyDetail = $this->contractMilestonePenaltyDetailRepo->update($fakeContractMilestonePenaltyDetail, $contractMilestonePenaltyDetail->id);

        $this->assertModelData($fakeContractMilestonePenaltyDetail, $updatedContractMilestonePenaltyDetail->toArray());
        $dbContractMilestonePenaltyDetail = $this->contractMilestonePenaltyDetailRepo->find($contractMilestonePenaltyDetail->id);
        $this->assertModelData($fakeContractMilestonePenaltyDetail, $dbContractMilestonePenaltyDetail->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_milestone_penalty_detail()
    {
        $contractMilestonePenaltyDetail = ContractMilestonePenaltyDetail::factory()->create();

        $resp = $this->contractMilestonePenaltyDetailRepo->delete($contractMilestonePenaltyDetail->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractMilestonePenaltyDetail::find($contractMilestonePenaltyDetail->id), 'ContractMilestonePenaltyDetail should not exist in DB');
    }
}
