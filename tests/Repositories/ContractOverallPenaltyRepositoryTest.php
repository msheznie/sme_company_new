<?php namespace Tests\Repositories;

use App\Models\ContractOverallPenalty;
use App\Repositories\ContractOverallPenaltyRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractOverallPenaltyRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractOverallPenaltyRepository
     */
    protected $contractOverallPenaltyRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractOverallPenaltyRepo = \App::make(ContractOverallPenaltyRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_overall_penalty()
    {
        $contractOverallPenalty = ContractOverallPenalty::factory()->make()->toArray();

        $createdContractOverallPenalty = $this->contractOverallPenaltyRepo->create($contractOverallPenalty);

        $createdContractOverallPenalty = $createdContractOverallPenalty->toArray();
        $this->assertArrayHasKey('id', $createdContractOverallPenalty);
        $this->assertNotNull($createdContractOverallPenalty['id'], 'Created ContractOverallPenalty must have id specified');
        $this->assertNotNull(ContractOverallPenalty::find($createdContractOverallPenalty['id']), 'ContractOverallPenalty with given id must be in DB');
        $this->assertModelData($contractOverallPenalty, $createdContractOverallPenalty);
    }

    /**
     * @test read
     */
    public function test_read_contract_overall_penalty()
    {
        $contractOverallPenalty = ContractOverallPenalty::factory()->create();

        $dbContractOverallPenalty = $this->contractOverallPenaltyRepo->find($contractOverallPenalty->id);

        $dbContractOverallPenalty = $dbContractOverallPenalty->toArray();
        $this->assertModelData($contractOverallPenalty->toArray(), $dbContractOverallPenalty);
    }

    /**
     * @test update
     */
    public function test_update_contract_overall_penalty()
    {
        $contractOverallPenalty = ContractOverallPenalty::factory()->create();
        $fakeContractOverallPenalty = ContractOverallPenalty::factory()->make()->toArray();

        $updatedContractOverallPenalty = $this->contractOverallPenaltyRepo->update($fakeContractOverallPenalty, $contractOverallPenalty->id);

        $this->assertModelData($fakeContractOverallPenalty, $updatedContractOverallPenalty->toArray());
        $dbContractOverallPenalty = $this->contractOverallPenaltyRepo->find($contractOverallPenalty->id);
        $this->assertModelData($fakeContractOverallPenalty, $dbContractOverallPenalty->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_overall_penalty()
    {
        $contractOverallPenalty = ContractOverallPenalty::factory()->create();

        $resp = $this->contractOverallPenaltyRepo->delete($contractOverallPenalty->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractOverallPenalty::find($contractOverallPenalty->id), 'ContractOverallPenalty should not exist in DB');
    }
}
