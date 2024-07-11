<?php namespace Tests\Repositories;

use App\Models\ContractHistory;
use App\Repositories\ContractHistoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractHistoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractHistoryRepository
     */
    protected $contractHistoryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractHistoryRepo = \App::make(ContractHistoryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_history()
    {
        $contractHistory = ContractHistory::factory()->make()->toArray();

        $createdContractHistory = $this->contractHistoryRepo->create($contractHistory);

        $createdContractHistory = $createdContractHistory->toArray();
        $this->assertArrayHasKey('id', $createdContractHistory);
        $this->assertNotNull($createdContractHistory['id'], 'Created ContractHistory must have id specified');
        $this->assertNotNull(ContractHistory::find($createdContractHistory['id']), 'ContractHistory with given id must be in DB');
        $this->assertModelData($contractHistory, $createdContractHistory);
    }

    /**
     * @test read
     */
    public function test_read_contract_history()
    {
        $contractHistory = ContractHistory::factory()->create();

        $dbContractHistory = $this->contractHistoryRepo->find($contractHistory->id);

        $dbContractHistory = $dbContractHistory->toArray();
        $this->assertModelData($contractHistory->toArray(), $dbContractHistory);
    }

    /**
     * @test update
     */
    public function test_update_contract_history()
    {
        $contractHistory = ContractHistory::factory()->create();
        $fakeContractHistory = ContractHistory::factory()->make()->toArray();

        $updatedContractHistory = $this->contractHistoryRepo->update($fakeContractHistory, $contractHistory->id);

        $this->assertModelData($fakeContractHistory, $updatedContractHistory->toArray());
        $dbContractHistory = $this->contractHistoryRepo->find($contractHistory->id);
        $this->assertModelData($fakeContractHistory, $dbContractHistory->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_history()
    {
        $contractHistory = ContractHistory::factory()->create();

        $resp = $this->contractHistoryRepo->delete($contractHistory->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractHistory::find($contractHistory->id), 'ContractHistory should not exist in DB');
    }
}
