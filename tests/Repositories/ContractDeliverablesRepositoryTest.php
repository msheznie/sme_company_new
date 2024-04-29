<?php namespace Tests\Repositories;

use App\Models\ContractDeliverables;
use App\Repositories\ContractDeliverablesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractDeliverablesRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractDeliverablesRepository
     */
    protected $contractDeliverablesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractDeliverablesRepo = \App::make(ContractDeliverablesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_deliverables()
    {
        $contractDeliverables = ContractDeliverables::factory()->make()->toArray();

        $createdContractDeliverables = $this->contractDeliverablesRepo->create($contractDeliverables);

        $createdContractDeliverables = $createdContractDeliverables->toArray();
        $this->assertArrayHasKey('id', $createdContractDeliverables);
        $this->assertNotNull($createdContractDeliverables['id'], 'Created ContractDeliverables must have id specified');
        $this->assertNotNull(ContractDeliverables::find($createdContractDeliverables['id']), 'ContractDeliverables with given id must be in DB');
        $this->assertModelData($contractDeliverables, $createdContractDeliverables);
    }

    /**
     * @test read
     */
    public function test_read_contract_deliverables()
    {
        $contractDeliverables = ContractDeliverables::factory()->create();

        $dbContractDeliverables = $this->contractDeliverablesRepo->find($contractDeliverables->id);

        $dbContractDeliverables = $dbContractDeliverables->toArray();
        $this->assertModelData($contractDeliverables->toArray(), $dbContractDeliverables);
    }

    /**
     * @test update
     */
    public function test_update_contract_deliverables()
    {
        $contractDeliverables = ContractDeliverables::factory()->create();
        $fakeContractDeliverables = ContractDeliverables::factory()->make()->toArray();

        $updatedContractDeliverables = $this->contractDeliverablesRepo->update($fakeContractDeliverables, $contractDeliverables->id);

        $this->assertModelData($fakeContractDeliverables, $updatedContractDeliverables->toArray());
        $dbContractDeliverables = $this->contractDeliverablesRepo->find($contractDeliverables->id);
        $this->assertModelData($fakeContractDeliverables, $dbContractDeliverables->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_deliverables()
    {
        $contractDeliverables = ContractDeliverables::factory()->create();

        $resp = $this->contractDeliverablesRepo->delete($contractDeliverables->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractDeliverables::find($contractDeliverables->id), 'ContractDeliverables should not exist in DB');
    }
}
