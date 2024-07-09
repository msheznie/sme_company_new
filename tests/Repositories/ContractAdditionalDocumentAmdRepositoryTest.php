<?php namespace Tests\Repositories;

use App\Models\ContractAdditionalDocumentAmd;
use App\Repositories\ContractAdditionalDocumentAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ContractAdditionalDocumentAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ContractAdditionalDocumentAmdRepository
     */
    protected $contractAdditionalDocumentAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->contractAdditionalDocumentAmdRepo = \App::make(ContractAdditionalDocumentAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_contract_additional_document_amd()
    {
        $contractAdditionalDocumentAmd = ContractAdditionalDocumentAmd::factory()->make()->toArray();

        $createdContractAdditionalDocumentAmd = $this->contractAdditionalDocumentAmdRepo->create($contractAdditionalDocumentAmd);

        $createdContractAdditionalDocumentAmd = $createdContractAdditionalDocumentAmd->toArray();
        $this->assertArrayHasKey('id', $createdContractAdditionalDocumentAmd);
        $this->assertNotNull($createdContractAdditionalDocumentAmd['id'], 'Created ContractAdditionalDocumentAmd must have id specified');
        $this->assertNotNull(ContractAdditionalDocumentAmd::find($createdContractAdditionalDocumentAmd['id']), 'ContractAdditionalDocumentAmd with given id must be in DB');
        $this->assertModelData($contractAdditionalDocumentAmd, $createdContractAdditionalDocumentAmd);
    }

    /**
     * @test read
     */
    public function test_read_contract_additional_document_amd()
    {
        $contractAdditionalDocumentAmd = ContractAdditionalDocumentAmd::factory()->create();

        $dbContractAdditionalDocumentAmd = $this->contractAdditionalDocumentAmdRepo->find($contractAdditionalDocumentAmd->id);

        $dbContractAdditionalDocumentAmd = $dbContractAdditionalDocumentAmd->toArray();
        $this->assertModelData($contractAdditionalDocumentAmd->toArray(), $dbContractAdditionalDocumentAmd);
    }

    /**
     * @test update
     */
    public function test_update_contract_additional_document_amd()
    {
        $contractAdditionalDocumentAmd = ContractAdditionalDocumentAmd::factory()->create();
        $fakeContractAdditionalDocumentAmd = ContractAdditionalDocumentAmd::factory()->make()->toArray();

        $updatedContractAdditionalDocumentAmd = $this->contractAdditionalDocumentAmdRepo->update($fakeContractAdditionalDocumentAmd, $contractAdditionalDocumentAmd->id);

        $this->assertModelData($fakeContractAdditionalDocumentAmd, $updatedContractAdditionalDocumentAmd->toArray());
        $dbContractAdditionalDocumentAmd = $this->contractAdditionalDocumentAmdRepo->find($contractAdditionalDocumentAmd->id);
        $this->assertModelData($fakeContractAdditionalDocumentAmd, $dbContractAdditionalDocumentAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_contract_additional_document_amd()
    {
        $contractAdditionalDocumentAmd = ContractAdditionalDocumentAmd::factory()->create();

        $resp = $this->contractAdditionalDocumentAmdRepo->delete($contractAdditionalDocumentAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(ContractAdditionalDocumentAmd::find($contractAdditionalDocumentAmd->id), 'ContractAdditionalDocumentAmd should not exist in DB');
    }
}
