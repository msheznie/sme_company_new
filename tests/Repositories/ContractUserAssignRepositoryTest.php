<?php namespace Tests\Repositories;

use App\Models\ContractUserAssign;
use App\Repositories\ContractUserAssignRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractUserAssignRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractUserAssignRepository
     */
    protected $contractUserAssignRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractUserAssignRepo = \App::make(ContractUserAssignRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_user_assign()
    {
        $contractUserAssign = ContractUserAssign::factory()->make()->toArray();

        $createdContractUserAssign = $this->contractUserAssignRepo->create($contractUserAssign);

        $createdContractUserAssign = $createdContractUserAssign->toArray();
        $this->assertArrayHasKey('id', $createdContractUserAssign);
        $this->assertNotNull($createdContractUserAssign['id'], 'Created ContractUserAssign must have id specified');
        $this->assertNotNull(ContractUserAssign::find($createdContractUserAssign['id']), 'ContractUserAssign with given id must be in DB');
        $this->assertModelData($contractUserAssign, $createdContractUserAssign);
    }

    /**
     * @test read
     */
    public function test_read_contract_user_assign()
    {
        $contractUserAssign = ContractUserAssign::factory()->create();

        $dbContractUserAssign = $this->contractUserAssignRepo->find($contractUserAssign->id);

        $dbContractUserAssign = $dbContractUserAssign->toArray();
        $this->assertModelData($contractUserAssign->toArray(), $dbContractUserAssign);
    }

    /**
     * @test update
     */
    public function test_update_contract_user_assign()
    {
        $contractUserAssign = ContractUserAssign::factory()->create();
        $fakeContractUserAssign = ContractUserAssign::factory()->make()->toArray();

        $updatedContractUserAssign = $this->contractUserAssignRepo->update($fakeContractUserAssign, $contractUserAssign->id);

        $this->assertModelData($fakeContractUserAssign, $updatedContractUserAssign->toArray());
        $dbContractUserAssign = $this->contractUserAssignRepo->find($contractUserAssign->id);
        $this->assertModelData($fakeContractUserAssign, $dbContractUserAssign->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_user_assign()
    {
        $contractUserAssign = ContractUserAssign::factory()->create();

        $resp = $this->contractUserAssignRepo->delete($contractUserAssign->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractUserAssign::find($contractUserAssign->id), 'ContractUserAssign should not exist in DB');
    }
}
