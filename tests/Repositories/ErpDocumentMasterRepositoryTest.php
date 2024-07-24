<?php namespace Tests\Repositories;

use App\Models\ErpDocumentMaster;
use App\Repositories\ErpDocumentMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ErpDocumentMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ErpDocumentMasterRepository
     */
    protected $erpDocumentMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->erpDocumentMasterRepo = \App::make(ErpDocumentMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_erp_document_master()
    {
        $erpDocumentMaster = ErpDocumentMaster::factory()->make()->toArray();

        $createdErpDocumentMaster = $this->erpDocumentMasterRepo->create($erpDocumentMaster);

        $createdErpDocumentMaster = $createdErpDocumentMaster->toArray();
        $this->assertArrayHasKey('id', $createdErpDocumentMaster);
        $this->assertNotNull($createdErpDocumentMaster['id'], 'Created ErpDocumentMaster must have id specified');
        $this->assertNotNull(ErpDocumentMaster::find($createdErpDocumentMaster['id']), 'ErpDocumentMaster with given id must be in DB');
        $this->assertModelData($erpDocumentMaster, $createdErpDocumentMaster);
    }

    /**
     * @test read
     */
    public function test_read_erp_document_master()
    {
        $erpDocumentMaster = ErpDocumentMaster::factory()->create();

        $dbErpDocumentMaster = $this->erpDocumentMasterRepo->find($erpDocumentMaster->id);

        $dbErpDocumentMaster = $dbErpDocumentMaster->toArray();
        $this->assertModelData($erpDocumentMaster->toArray(), $dbErpDocumentMaster);
    }

    /**
     * @test update
     */
    public function test_update_erp_document_master()
    {
        $erpDocumentMaster = ErpDocumentMaster::factory()->create();
        $fakeErpDocumentMaster = ErpDocumentMaster::factory()->make()->toArray();

        $updatedErpDocumentMaster = $this->erpDocumentMasterRepo->update($fakeErpDocumentMaster, $erpDocumentMaster->id);

        $this->assertModelData($fakeErpDocumentMaster, $updatedErpDocumentMaster->toArray());
        $dbErpDocumentMaster = $this->erpDocumentMasterRepo->find($erpDocumentMaster->id);
        $this->assertModelData($fakeErpDocumentMaster, $dbErpDocumentMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_erp_document_master()
    {
        $erpDocumentMaster = ErpDocumentMaster::factory()->create();

        $resp = $this->erpDocumentMasterRepo->delete($erpDocumentMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(ErpDocumentMaster::find($erpDocumentMaster->id), 'ErpDocumentMaster should not exist in DB');
    }
}
