<?php namespace Tests\Repositories;

use App\Models\ContractUserGroup;
use App\Repositories\ContractUserGroupRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractUserGroupRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractUserGroupRepository
     */
    protected $contractUserGroupRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractUserGroupRepo = \App::make(ContractUserGroupRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_user_group()
    {
        $contractUserGroup = ContractUserGroup::factory()->make()->toArray();

        $createdContractUserGroup = $this->contractUserGroupRepo->create($contractUserGroup);

        $createdContractUserGroup = $createdContractUserGroup->toArray();
        $this->assertArrayHasKey('id', $createdContractUserGroup);
        $this->assertNotNull($createdContractUserGroup['id'], 'Created ContractUserGroup must have id specified');
        $this->assertNotNull(ContractUserGroup::find($createdContractUserGroup['id']), 'ContractUserGroup with given id must be in DB');
        $this->assertModelData($contractUserGroup, $createdContractUserGroup);
    }

    /**
     * @test read
     */
    public function test_read_contract_user_group()
    {
        $contractUserGroup = ContractUserGroup::factory()->create();

        $dbContractUserGroup = $this->contractUserGroupRepo->find($contractUserGroup->id);

        $dbContractUserGroup = $dbContractUserGroup->toArray();
        $this->assertModelData($contractUserGroup->toArray(), $dbContractUserGroup);
    }

    /**
     * @test update
     */
    public function test_update_contract_user_group()
    {
        $contractUserGroup = ContractUserGroup::factory()->create();
        $fakeContractUserGroup = ContractUserGroup::factory()->make()->toArray();

        $updatedContractUserGroup = $this->contractUserGroupRepo->update($fakeContractUserGroup, $contractUserGroup->id);

        $this->assertModelData($fakeContractUserGroup, $updatedContractUserGroup->toArray());
        $dbContractUserGroup = $this->contractUserGroupRepo->find($contractUserGroup->id);
        $this->assertModelData($fakeContractUserGroup, $dbContractUserGroup->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_user_group()
    {
        $contractUserGroup = ContractUserGroup::factory()->create();

        $resp = $this->contractUserGroupRepo->delete($contractUserGroup->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractUserGroup::find($contractUserGroup->id), 'ContractUserGroup should not exist in DB');
    }
}
