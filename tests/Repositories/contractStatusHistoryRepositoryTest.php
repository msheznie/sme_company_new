<?php namespace Tests\Repositories;

use App\Models\contractStatusHistory;
use App\Repositories\contractStatusHistoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class contractStatusHistoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var contractStatusHistoryRepository
     */
    protected $contractStatusHistoryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractStatusHistoryRepo = \App::make(contractStatusHistoryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_status_history()
    {
        $contractStatusHistory = contractStatusHistory::factory()->make()->toArray();

        $createdcontractStatusHistory = $this->contractStatusHistoryRepo->create($contractStatusHistory);

        $createdcontractStatusHistory = $createdcontractStatusHistory->toArray();
        $this->assertArrayHasKey('id', $createdcontractStatusHistory);
        $this->assertNotNull($createdcontractStatusHistory['id'], 'Created contractStatusHistory must have id specified');
        $this->assertNotNull(contractStatusHistory::find($createdcontractStatusHistory['id']), 'contractStatusHistory with given id must be in DB');
        $this->assertModelData($contractStatusHistory, $createdcontractStatusHistory);
    }

    /**
     * @test read
     */
    public function test_read_contract_status_history()
    {
        $contractStatusHistory = contractStatusHistory::factory()->create();

        $dbcontractStatusHistory = $this->contractStatusHistoryRepo->find($contractStatusHistory->id);

        $dbcontractStatusHistory = $dbcontractStatusHistory->toArray();
        $this->assertModelData($contractStatusHistory->toArray(), $dbcontractStatusHistory);
    }

    /**
     * @test update
     */
    public function test_update_contract_status_history()
    {
        $contractStatusHistory = contractStatusHistory::factory()->create();
        $fakecontractStatusHistory = contractStatusHistory::factory()->make()->toArray();

        $updatedcontractStatusHistory = $this->contractStatusHistoryRepo->update($fakecontractStatusHistory, $contractStatusHistory->id);

        $this->assertModelData($fakecontractStatusHistory, $updatedcontractStatusHistory->toArray());
        $dbcontractStatusHistory = $this->contractStatusHistoryRepo->find($contractStatusHistory->id);
        $this->assertModelData($fakecontractStatusHistory, $dbcontractStatusHistory->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_status_history()
    {
        $contractStatusHistory = contractStatusHistory::factory()->create();

        $resp = $this->contractStatusHistoryRepo->delete($contractStatusHistory->id);

        $this->assertTrue($resp);
        $this->assertNull(contractStatusHistory::find($contractStatusHistory->id), 'contractStatusHistory should not exist in DB');
    }
}
