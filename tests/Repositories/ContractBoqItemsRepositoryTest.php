<?php namespace Tests\Repositories;

use App\Models\ContractBoqItems;
use App\Repositories\ContractBoqItemsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractBoqItemsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractBoqItemsRepository
     */
    protected $contractBoqItemsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractBoqItemsRepo = \App::make(ContractBoqItemsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_boq_items()
    {
        $contractBoqItems = ContractBoqItems::factory()->make()->toArray();

        $createdContractBoqItems = $this->contractBoqItemsRepo->create($contractBoqItems);

        $createdContractBoqItems = $createdContractBoqItems->toArray();
        $this->assertArrayHasKey('id', $createdContractBoqItems);
        $this->assertNotNull($createdContractBoqItems['id'], 'Created ContractBoqItems must have id specified');
        $this->assertNotNull(ContractBoqItems::find($createdContractBoqItems['id']), 'ContractBoqItems with given id must be in DB');
        $this->assertModelData($contractBoqItems, $createdContractBoqItems);
    }

    /**
     * @test read
     */
    public function test_read_contract_boq_items()
    {
        $contractBoqItems = ContractBoqItems::factory()->create();

        $dbContractBoqItems = $this->contractBoqItemsRepo->find($contractBoqItems->id);

        $dbContractBoqItems = $dbContractBoqItems->toArray();
        $this->assertModelData($contractBoqItems->toArray(), $dbContractBoqItems);
    }

    /**
     * @test update
     */
    public function test_update_contract_boq_items()
    {
        $contractBoqItems = ContractBoqItems::factory()->create();
        $fakeContractBoqItems = ContractBoqItems::factory()->make()->toArray();

        $updatedContractBoqItems = $this->contractBoqItemsRepo->update($fakeContractBoqItems, $contractBoqItems->id);

        $this->assertModelData($fakeContractBoqItems, $updatedContractBoqItems->toArray());
        $dbContractBoqItems = $this->contractBoqItemsRepo->find($contractBoqItems->id);
        $this->assertModelData($fakeContractBoqItems, $dbContractBoqItems->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_boq_items()
    {
        $contractBoqItems = ContractBoqItems::factory()->create();

        $resp = $this->contractBoqItemsRepo->delete($contractBoqItems->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractBoqItems::find($contractBoqItems->id), 'ContractBoqItems should not exist in DB');
    }
}
