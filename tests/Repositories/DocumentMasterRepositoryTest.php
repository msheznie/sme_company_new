<?php namespace Tests\Repositories;

use App\Models\DocumentMaster;
use App\Repositories\DocumentMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DocumentMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DocumentMasterRepository
     */
    protected $documentMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->documentMasterRepo = \App::make(DocumentMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_document_master()
    {
        $documentMaster = DocumentMaster::factory()->make()->toArray();

        $createdDocumentMaster = $this->documentMasterRepo->create($documentMaster);

        $createdDocumentMaster = $createdDocumentMaster->toArray();
        $this->assertArrayHasKey('id', $createdDocumentMaster);
        $this->assertNotNull($createdDocumentMaster['id'], 'Created DocumentMaster must have id specified');
        $this->assertNotNull(DocumentMaster::find($createdDocumentMaster['id']), 'DocumentMaster with given id must be in DB');
        $this->assertModelData($documentMaster, $createdDocumentMaster);
    }

    /**
     * @test read
     */
    public function test_read_document_master()
    {
        $documentMaster = DocumentMaster::factory()->create();

        $dbDocumentMaster = $this->documentMasterRepo->find($documentMaster->id);

        $dbDocumentMaster = $dbDocumentMaster->toArray();
        $this->assertModelData($documentMaster->toArray(), $dbDocumentMaster);
    }

    /**
     * @test update
     */
    public function test_update_document_master()
    {
        $documentMaster = DocumentMaster::factory()->create();
        $fakeDocumentMaster = DocumentMaster::factory()->make()->toArray();

        $updatedDocumentMaster = $this->documentMasterRepo->update($fakeDocumentMaster, $documentMaster->id);

        $this->assertModelData($fakeDocumentMaster, $updatedDocumentMaster->toArray());
        $dbDocumentMaster = $this->documentMasterRepo->find($documentMaster->id);
        $this->assertModelData($fakeDocumentMaster, $dbDocumentMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_document_master()
    {
        $documentMaster = DocumentMaster::factory()->create();

        $resp = $this->documentMasterRepo->delete($documentMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(DocumentMaster::find($documentMaster->id), 'DocumentMaster should not exist in DB');
    }
}
