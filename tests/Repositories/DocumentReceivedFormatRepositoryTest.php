<?php namespace Tests\Repositories;

use App\Models\DocumentReceivedFormat;
use App\Repositories\DocumentReceivedFormatRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DocumentReceivedFormatRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DocumentReceivedFormatRepository
     */
    protected $documentReceivedFormatRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->documentReceivedFormatRepo = \App::make(DocumentReceivedFormatRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_document_received_format()
    {
        $documentReceivedFormat = DocumentReceivedFormat::factory()->make()->toArray();

        $createdDocumentReceivedFormat = $this->documentReceivedFormatRepo->create($documentReceivedFormat);

        $createdDocumentReceivedFormat = $createdDocumentReceivedFormat->toArray();
        $this->assertArrayHasKey('id', $createdDocumentReceivedFormat);
        $this->assertNotNull($createdDocumentReceivedFormat['id'], 'Created DocumentReceivedFormat must have id specified');
        $this->assertNotNull(DocumentReceivedFormat::find($createdDocumentReceivedFormat['id']), 'DocumentReceivedFormat with given id must be in DB');
        $this->assertModelData($documentReceivedFormat, $createdDocumentReceivedFormat);
    }

    /**
     * @test read
     */
    public function test_read_document_received_format()
    {
        $documentReceivedFormat = DocumentReceivedFormat::factory()->create();

        $dbDocumentReceivedFormat = $this->documentReceivedFormatRepo->find($documentReceivedFormat->id);

        $dbDocumentReceivedFormat = $dbDocumentReceivedFormat->toArray();
        $this->assertModelData($documentReceivedFormat->toArray(), $dbDocumentReceivedFormat);
    }

    /**
     * @test update
     */
    public function test_update_document_received_format()
    {
        $documentReceivedFormat = DocumentReceivedFormat::factory()->create();
        $fakeDocumentReceivedFormat = DocumentReceivedFormat::factory()->make()->toArray();

        $updatedDocumentReceivedFormat = $this->documentReceivedFormatRepo->update($fakeDocumentReceivedFormat, $documentReceivedFormat->id);

        $this->assertModelData($fakeDocumentReceivedFormat, $updatedDocumentReceivedFormat->toArray());
        $dbDocumentReceivedFormat = $this->documentReceivedFormatRepo->find($documentReceivedFormat->id);
        $this->assertModelData($fakeDocumentReceivedFormat, $dbDocumentReceivedFormat->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_document_received_format()
    {
        $documentReceivedFormat = DocumentReceivedFormat::factory()->create();

        $resp = $this->documentReceivedFormatRepo->delete($documentReceivedFormat->id);

        $this->assertTrue($resp);
        $this->assertNull(DocumentReceivedFormat::find($documentReceivedFormat->id), 'DocumentReceivedFormat should not exist in DB');
    }
}
