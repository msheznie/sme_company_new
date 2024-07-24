<?php namespace Tests\Repositories;

use App\Models\ContractUsers;
use App\Repositories\ContractUsersRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractUsersRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractUsersRepository
     */
    protected $contractUsersRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractUsersRepo = \App::make(ContractUsersRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_users()
    {
        $contractUsers = ContractUsers::factory()->make()->toArray();

        $createdContractUsers = $this->contractUsersRepo->create($contractUsers);

        $createdContractUsers = $createdContractUsers->toArray();
        $this->assertArrayHasKey('id', $createdContractUsers);
        $this->assertNotNull($createdContractUsers['id'], 'Created ContractUsers must have id specified');
        $this->assertNotNull(ContractUsers::find($createdContractUsers['id']), 'ContractUsers with given id must be in DB');
        $this->assertModelData($contractUsers, $createdContractUsers);
    }

    /**
     * @test read
     */
    public function test_read_contract_users()
    {
        $contractUsers = ContractUsers::factory()->create();

        $dbContractUsers = $this->contractUsersRepo->find($contractUsers->id);

        $dbContractUsers = $dbContractUsers->toArray();
        $this->assertModelData($contractUsers->toArray(), $dbContractUsers);
    }

    /**
     * @test update
     */
    public function test_update_contract_users()
    {
        $contractUsers = ContractUsers::factory()->create();
        $fakeContractUsers = ContractUsers::factory()->make()->toArray();

        $updatedContractUsers = $this->contractUsersRepo->update($fakeContractUsers, $contractUsers->id);

        $this->assertModelData($fakeContractUsers, $updatedContractUsers->toArray());
        $dbContractUsers = $this->contractUsersRepo->find($contractUsers->id);
        $this->assertModelData($fakeContractUsers, $dbContractUsers->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_users()
    {
        $contractUsers = ContractUsers::factory()->create();

        $resp = $this->contractUsersRepo->delete($contractUsers->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractUsers::find($contractUsers->id), 'ContractUsers should not exist in DB');
    }
}
