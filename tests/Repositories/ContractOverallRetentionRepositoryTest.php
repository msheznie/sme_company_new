<?php namespace Tests\Repositories;

use App\Models\ContractOverallRetention;
use App\Repositories\ContractOverallRetentionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractOverallRetentionRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractOverallRetentionRepository
     */
    protected $contractOverallRetentionRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractOverallRetentionRepo = \App::make(ContractOverallRetentionRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_overall_retention()
    {
        $contractOverallRetention = ContractOverallRetention::factory()->make()->toArray();

        $createdContractOverallRetention = $this->contractOverallRetentionRepo->create($contractOverallRetention);

        $createdContractOverallRetention = $createdContractOverallRetention->toArray();
        $this->assertArrayHasKey('id', $createdContractOverallRetention);
        $this->assertNotNull($createdContractOverallRetention['id'], 'Created ContractOverallRetention must have id specified');
        $this->assertNotNull(ContractOverallRetention::find($createdContractOverallRetention['id']), 'ContractOverallRetention with given id must be in DB');
        $this->assertModelData($contractOverallRetention, $createdContractOverallRetention);
    }

    /**
     * @test read
     */
    public function test_read_contract_overall_retention()
    {
        $contractOverallRetention = ContractOverallRetention::factory()->create();

        $dbContractOverallRetention = $this->contractOverallRetentionRepo->find($contractOverallRetention->id);

        $dbContractOverallRetention = $dbContractOverallRetention->toArray();
        $this->assertModelData($contractOverallRetention->toArray(), $dbContractOverallRetention);
    }

    /**
     * @test update
     */
    public function test_update_contract_overall_retention()
    {
        $contractOverallRetention = ContractOverallRetention::factory()->create();
        $fakeContractOverallRetention = ContractOverallRetention::factory()->make()->toArray();

        $updatedContractOverallRetention = $this->contractOverallRetentionRepo->update($fakeContractOverallRetention, $contractOverallRetention->id);

        $this->assertModelData($fakeContractOverallRetention, $updatedContractOverallRetention->toArray());
        $dbContractOverallRetention = $this->contractOverallRetentionRepo->find($contractOverallRetention->id);
        $this->assertModelData($fakeContractOverallRetention, $dbContractOverallRetention->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_overall_retention()
    {
        $contractOverallRetention = ContractOverallRetention::factory()->create();

        $resp = $this->contractOverallRetentionRepo->delete($contractOverallRetention->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractOverallRetention::find($contractOverallRetention->id), 'ContractOverallRetention should not exist in DB');
    }
}
