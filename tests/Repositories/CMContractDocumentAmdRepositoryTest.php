<?php namespace Tests\Repositories;

use App\Models\CMContractDocumentAmd;
use App\Repositories\CMContractDocumentAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractDocumentAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractDocumentAmdRepository
     */
    protected $cMContractDocumentAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractDocumentAmdRepo = \App::make(CMContractDocumentAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_document_amd()
    {
        $cMContractDocumentAmd = CMContractDocumentAmd::factory()->make()->toArray();

        $createdCMContractDocumentAmd = $this->cMContractDocumentAmdRepo->create($cMContractDocumentAmd);

        $createdCMContractDocumentAmd = $createdCMContractDocumentAmd->toArray();
        $this->assertArrayHasKey('id', $createdCMContractDocumentAmd);
        $this->assertNotNull($createdCMContractDocumentAmd['id'], 'Created CMContractDocumentAmd must have id specified');
        $this->assertNotNull(CMContractDocumentAmd::find($createdCMContractDocumentAmd['id']), 'CMContractDocumentAmd with given id must be in DB');
        $this->assertModelData($cMContractDocumentAmd, $createdCMContractDocumentAmd);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_document_amd()
    {
        $cMContractDocumentAmd = CMContractDocumentAmd::factory()->create();

        $dbCMContractDocumentAmd = $this->cMContractDocumentAmdRepo->find($cMContractDocumentAmd->id);

        $dbCMContractDocumentAmd = $dbCMContractDocumentAmd->toArray();
        $this->assertModelData($cMContractDocumentAmd->toArray(), $dbCMContractDocumentAmd);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_document_amd()
    {
        $cMContractDocumentAmd = CMContractDocumentAmd::factory()->create();
        $fakeCMContractDocumentAmd = CMContractDocumentAmd::factory()->make()->toArray();

        $updatedCMContractDocumentAmd = $this->cMContractDocumentAmdRepo->update($fakeCMContractDocumentAmd, $cMContractDocumentAmd->id);

        $this->assertModelData($fakeCMContractDocumentAmd, $updatedCMContractDocumentAmd->toArray());
        $dbCMContractDocumentAmd = $this->cMContractDocumentAmdRepo->find($cMContractDocumentAmd->id);
        $this->assertModelData($fakeCMContractDocumentAmd, $dbCMContractDocumentAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_document_amd()
    {
        $cMContractDocumentAmd = CMContractDocumentAmd::factory()->create();

        $resp = $this->cMContractDocumentAmdRepo->delete($cMContractDocumentAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractDocumentAmd::find($cMContractDocumentAmd->id), 'CMContractDocumentAmd should not exist in DB');
    }
}
