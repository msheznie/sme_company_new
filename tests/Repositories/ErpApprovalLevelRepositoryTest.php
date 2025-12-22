<?php namespace Tests\Repositories;

use App\Models\ErpApprovalLevel;
use App\Repositories\ErpApprovalLevelRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ErpApprovalLevelRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ErpApprovalLevelRepository
     */
    protected $erpApprovalLevelRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->erpApprovalLevelRepo = \App::make(ErpApprovalLevelRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_erp_approval_level()
    {
        $erpApprovalLevel = ErpApprovalLevel::factory()->make()->toArray();

        $createdErpApprovalLevel = $this->erpApprovalLevelRepo->create($erpApprovalLevel);

        $createdErpApprovalLevel = $createdErpApprovalLevel->toArray();
        $this->assertArrayHasKey('id', $createdErpApprovalLevel);
        $this->assertNotNull($createdErpApprovalLevel['id'], 'Created ErpApprovalLevel must have id specified');
        $this->assertNotNull(ErpApprovalLevel::find($createdErpApprovalLevel['id']), 'ErpApprovalLevel with given id must be in DB');
        $this->assertModelData($erpApprovalLevel, $createdErpApprovalLevel);
    }

    /**
     * @test read
     */
    public function test_read_erp_approval_level()
    {
        $erpApprovalLevel = ErpApprovalLevel::factory()->create();

        $dbErpApprovalLevel = $this->erpApprovalLevelRepo->find($erpApprovalLevel->id);

        $dbErpApprovalLevel = $dbErpApprovalLevel->toArray();
        $this->assertModelData($erpApprovalLevel->toArray(), $dbErpApprovalLevel);
    }

    /**
     * @test update
     */
    public function test_update_erp_approval_level()
    {
        $erpApprovalLevel = ErpApprovalLevel::factory()->create();
        $fakeErpApprovalLevel = ErpApprovalLevel::factory()->make()->toArray();

        $updatedErpApprovalLevel = $this->erpApprovalLevelRepo->update($fakeErpApprovalLevel, $erpApprovalLevel->id);

        $this->assertModelData($fakeErpApprovalLevel, $updatedErpApprovalLevel->toArray());
        $dbErpApprovalLevel = $this->erpApprovalLevelRepo->find($erpApprovalLevel->id);
        $this->assertModelData($fakeErpApprovalLevel, $dbErpApprovalLevel->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_erp_approval_level()
    {
        $erpApprovalLevel = ErpApprovalLevel::factory()->create();

        $resp = $this->erpApprovalLevelRepo->delete($erpApprovalLevel->id);

        $this->assertTrue($resp);
        $this->assertNull(ErpApprovalLevel::find($erpApprovalLevel->id), 'ErpApprovalLevel should not exist in DB');
    }
}
