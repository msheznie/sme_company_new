<?php namespace Tests\Repositories;

use App\Models\ContractOverallPenaltyAmd;
use App\Repositories\ContractOverallPenaltyAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractOverallPenaltyAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractOverallPenaltyAmdRepository
     */
    protected $contractOverallPenaltyAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractOverallPenaltyAmdRepo = \App::make(ContractOverallPenaltyAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_overall_penalty_amd()
    {
        $contractOverallPenaltyAmd = ContractOverallPenaltyAmd::factory()->make()->toArray();

        $createdContractOverallPenaltyAmd = $this->contractOverallPenaltyAmdRepo->create($contractOverallPenaltyAmd);

        $createdContractOverallPenaltyAmd = $createdContractOverallPenaltyAmd->toArray();
        $this->assertArrayHasKey('id', $createdContractOverallPenaltyAmd);
        $this->assertNotNull($createdContractOverallPenaltyAmd['id'], 'Created ContractOverallPenaltyAmd must have id specified');
        $this->assertNotNull(ContractOverallPenaltyAmd::find($createdContractOverallPenaltyAmd['id']), 'ContractOverallPenaltyAmd with given id must be in DB');
        $this->assertModelData($contractOverallPenaltyAmd, $createdContractOverallPenaltyAmd);
    }

    /**
     * @test read
     */
    public function test_read_contract_overall_penalty_amd()
    {
        $contractOverallPenaltyAmd = ContractOverallPenaltyAmd::factory()->create();

        $dbContractOverallPenaltyAmd = $this->contractOverallPenaltyAmdRepo->find($contractOverallPenaltyAmd->id);

        $dbContractOverallPenaltyAmd = $dbContractOverallPenaltyAmd->toArray();
        $this->assertModelData($contractOverallPenaltyAmd->toArray(), $dbContractOverallPenaltyAmd);
    }

    /**
     * @test update
     */
    public function test_update_contract_overall_penalty_amd()
    {
        $contractOverallPenaltyAmd = ContractOverallPenaltyAmd::factory()->create();
        $fakeContractOverallPenaltyAmd = ContractOverallPenaltyAmd::factory()->make()->toArray();

        $updatedContractOverallPenaltyAmd = $this->contractOverallPenaltyAmdRepo->update($fakeContractOverallPenaltyAmd, $contractOverallPenaltyAmd->id);

        $this->assertModelData($fakeContractOverallPenaltyAmd, $updatedContractOverallPenaltyAmd->toArray());
        $dbContractOverallPenaltyAmd = $this->contractOverallPenaltyAmdRepo->find($contractOverallPenaltyAmd->id);
        $this->assertModelData($fakeContractOverallPenaltyAmd, $dbContractOverallPenaltyAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_overall_penalty_amd()
    {
        $contractOverallPenaltyAmd = ContractOverallPenaltyAmd::factory()->create();

        $resp = $this->contractOverallPenaltyAmdRepo->delete($contractOverallPenaltyAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractOverallPenaltyAmd::find($contractOverallPenaltyAmd->id), 'ContractOverallPenaltyAmd should not exist in DB');
    }
}
