<?php namespace Tests\Repositories;

use App\Models\ContractDocument;
use App\Repositories\ContractDocumentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractDocumentRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractDocumentRepository
     */
    protected $contractDocumentRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractDocumentRepo = \App::make(ContractDocumentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_document()
    {
        $contractDocument = ContractDocument::factory()->make()->toArray();

        $createdContractDocument = $this->contractDocumentRepo->create($contractDocument);

        $createdContractDocument = $createdContractDocument->toArray();
        $this->assertArrayHasKey('id', $createdContractDocument);
        $this->assertNotNull($createdContractDocument['id'], 'Created ContractDocument must have id specified');
        $this->assertNotNull(ContractDocument::find($createdContractDocument['id']), 'ContractDocument with given id must be in DB');
        $this->assertModelData($contractDocument, $createdContractDocument);
    }

    /**
     * @test read
     */
    public function test_read_contract_document()
    {
        $contractDocument = ContractDocument::factory()->create();

        $dbContractDocument = $this->contractDocumentRepo->find($contractDocument->id);

        $dbContractDocument = $dbContractDocument->toArray();
        $this->assertModelData($contractDocument->toArray(), $dbContractDocument);
    }

    /**
     * @test update
     */
    public function test_update_contract_document()
    {
        $contractDocument = ContractDocument::factory()->create();
        $fakeContractDocument = ContractDocument::factory()->make()->toArray();

        $updatedContractDocument = $this->contractDocumentRepo->update($fakeContractDocument, $contractDocument->id);

        $this->assertModelData($fakeContractDocument, $updatedContractDocument->toArray());
        $dbContractDocument = $this->contractDocumentRepo->find($contractDocument->id);
        $this->assertModelData($fakeContractDocument, $dbContractDocument->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_document()
    {
        $contractDocument = ContractDocument::factory()->create();

        $resp = $this->contractDocumentRepo->delete($contractDocument->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractDocument::find($contractDocument->id), 'ContractDocument should not exist in DB');
    }
}
