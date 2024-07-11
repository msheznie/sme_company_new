<?php namespace Tests\Repositories;

use App\Models\CMContractMasterAmd;
use App\Repositories\CMContractMasterAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractMasterAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractMasterAmdRepository
     */
    protected $cMContractMasterAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractMasterAmdRepo = \App::make(CMContractMasterAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_master_amd()
    {
        $cMContractMasterAmd = CMContractMasterAmd::factory()->make()->toArray();

        $createdCMContractMasterAmd = $this->cMContractMasterAmdRepo->create($cMContractMasterAmd);

        $createdCMContractMasterAmd = $createdCMContractMasterAmd->toArray();
        $this->assertArrayHasKey('id', $createdCMContractMasterAmd);
        $this->assertNotNull($createdCMContractMasterAmd['id'], 'Created CMContractMasterAmd must have id specified');
        $this->assertNotNull(CMContractMasterAmd::find($createdCMContractMasterAmd['id']), 'CMContractMasterAmd with given id must be in DB');
        $this->assertModelData($cMContractMasterAmd, $createdCMContractMasterAmd);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_master_amd()
    {
        $cMContractMasterAmd = CMContractMasterAmd::factory()->create();

        $dbCMContractMasterAmd = $this->cMContractMasterAmdRepo->find($cMContractMasterAmd->id);

        $dbCMContractMasterAmd = $dbCMContractMasterAmd->toArray();
        $this->assertModelData($cMContractMasterAmd->toArray(), $dbCMContractMasterAmd);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_master_amd()
    {
        $cMContractMasterAmd = CMContractMasterAmd::factory()->create();
        $fakeCMContractMasterAmd = CMContractMasterAmd::factory()->make()->toArray();

        $updatedCMContractMasterAmd = $this->cMContractMasterAmdRepo->update($fakeCMContractMasterAmd, $cMContractMasterAmd->id);

        $this->assertModelData($fakeCMContractMasterAmd, $updatedCMContractMasterAmd->toArray());
        $dbCMContractMasterAmd = $this->cMContractMasterAmdRepo->find($cMContractMasterAmd->id);
        $this->assertModelData($fakeCMContractMasterAmd, $dbCMContractMasterAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_master_amd()
    {
        $cMContractMasterAmd = CMContractMasterAmd::factory()->create();

        $resp = $this->cMContractMasterAmdRepo->delete($cMContractMasterAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractMasterAmd::find($cMContractMasterAmd->id), 'CMContractMasterAmd should not exist in DB');
    }
}
