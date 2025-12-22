<?php namespace Tests\Repositories;

use App\Models\ErpApprovalRole;
use App\Repositories\ErpApprovalRoleRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ErpApprovalRoleRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ErpApprovalRoleRepository
     */
    protected $erpApprovalRoleRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->erpApprovalRoleRepo = \App::make(ErpApprovalRoleRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_erp_approval_role()
    {
        $erpApprovalRole = ErpApprovalRole::factory()->make()->toArray();

        $createdErpApprovalRole = $this->erpApprovalRoleRepo->create($erpApprovalRole);

        $createdErpApprovalRole = $createdErpApprovalRole->toArray();
        $this->assertArrayHasKey('id', $createdErpApprovalRole);
        $this->assertNotNull($createdErpApprovalRole['id'], 'Created ErpApprovalRole must have id specified');
        $this->assertNotNull(ErpApprovalRole::find($createdErpApprovalRole['id']), 'ErpApprovalRole with given id must be in DB');
        $this->assertModelData($erpApprovalRole, $createdErpApprovalRole);
    }

    /**
     * @test read
     */
    public function test_read_erp_approval_role()
    {
        $erpApprovalRole = ErpApprovalRole::factory()->create();

        $dbErpApprovalRole = $this->erpApprovalRoleRepo->find($erpApprovalRole->id);

        $dbErpApprovalRole = $dbErpApprovalRole->toArray();
        $this->assertModelData($erpApprovalRole->toArray(), $dbErpApprovalRole);
    }

    /**
     * @test update
     */
    public function test_update_erp_approval_role()
    {
        $erpApprovalRole = ErpApprovalRole::factory()->create();
        $fakeErpApprovalRole = ErpApprovalRole::factory()->make()->toArray();

        $updatedErpApprovalRole = $this->erpApprovalRoleRepo->update($fakeErpApprovalRole, $erpApprovalRole->id);

        $this->assertModelData($fakeErpApprovalRole, $updatedErpApprovalRole->toArray());
        $dbErpApprovalRole = $this->erpApprovalRoleRepo->find($erpApprovalRole->id);
        $this->assertModelData($fakeErpApprovalRole, $dbErpApprovalRole->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_erp_approval_role()
    {
        $erpApprovalRole = ErpApprovalRole::factory()->create();

        $resp = $this->erpApprovalRoleRepo->delete($erpApprovalRole->id);

        $this->assertTrue($resp);
        $this->assertNull(ErpApprovalRole::find($erpApprovalRole->id), 'ErpApprovalRole should not exist in DB');
    }
}
