<?php namespace Tests\Repositories;

use App\Models\ContractAdditionalDocuments;
use App\Repositories\ContractAdditionalDocumentsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractAdditionalDocumentsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractAdditionalDocumentsRepository
     */
    protected $contractAdditionalDocumentsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractAdditionalDocumentsRepo = \App::make(ContractAdditionalDocumentsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_additional_documents()
    {
        $contractAdditionalDocuments = ContractAdditionalDocuments::factory()->make()->toArray();

        $createdContractAdditionalDocuments = $this->contractAdditionalDocumentsRepo->create($contractAdditionalDocuments);

        $createdContractAdditionalDocuments = $createdContractAdditionalDocuments->toArray();
        $this->assertArrayHasKey('id', $createdContractAdditionalDocuments);
        $this->assertNotNull($createdContractAdditionalDocuments['id'], 'Created ContractAdditionalDocuments must have id specified');
        $this->assertNotNull(ContractAdditionalDocuments::find($createdContractAdditionalDocuments['id']), 'ContractAdditionalDocuments with given id must be in DB');
        $this->assertModelData($contractAdditionalDocuments, $createdContractAdditionalDocuments);
    }

    /**
     * @test read
     */
    public function test_read_contract_additional_documents()
    {
        $contractAdditionalDocuments = ContractAdditionalDocuments::factory()->create();

        $dbContractAdditionalDocuments = $this->contractAdditionalDocumentsRepo->find($contractAdditionalDocuments->id);

        $dbContractAdditionalDocuments = $dbContractAdditionalDocuments->toArray();
        $this->assertModelData($contractAdditionalDocuments->toArray(), $dbContractAdditionalDocuments);
    }

    /**
     * @test update
     */
    public function test_update_contract_additional_documents()
    {
        $contractAdditionalDocuments = ContractAdditionalDocuments::factory()->create();
        $fakeContractAdditionalDocuments = ContractAdditionalDocuments::factory()->make()->toArray();

        $updatedContractAdditionalDocuments = $this->contractAdditionalDocumentsRepo->update($fakeContractAdditionalDocuments, $contractAdditionalDocuments->id);

        $this->assertModelData($fakeContractAdditionalDocuments, $updatedContractAdditionalDocuments->toArray());
        $dbContractAdditionalDocuments = $this->contractAdditionalDocumentsRepo->find($contractAdditionalDocuments->id);
        $this->assertModelData($fakeContractAdditionalDocuments, $dbContractAdditionalDocuments->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_additional_documents()
    {
        $contractAdditionalDocuments = ContractAdditionalDocuments::factory()->create();

        $resp = $this->contractAdditionalDocumentsRepo->delete($contractAdditionalDocuments->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractAdditionalDocuments::find($contractAdditionalDocuments->id), 'ContractAdditionalDocuments should not exist in DB');
    }
}
