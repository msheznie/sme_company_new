<?php namespace Tests\Repositories;

use App\Models\CMContractOverallRetentionAmd;
use App\Repositories\CMContractOverallRetentionAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractOverallRetentionAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractOverallRetentionAmdRepository
     */
    protected $cMContractOverallRetentionAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractOverallRetentionAmdRepo = \App::make(CMContractOverallRetentionAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_overall_retention_amd()
    {
        $cMContractOverallRetentionAmd = CMContractOverallRetentionAmd::factory()->make()->toArray();

        $createdCMContractOverallRetentionAmd = $this->cMContractOverallRetentionAmdRepo->create($cMContractOverallRetentionAmd);

        $createdCMContractOverallRetentionAmd = $createdCMContractOverallRetentionAmd->toArray();
        $this->assertArrayHasKey('id', $createdCMContractOverallRetentionAmd);
        $this->assertNotNull($createdCMContractOverallRetentionAmd['id'], 'Created CMContractOverallRetentionAmd must have id specified');
        $this->assertNotNull(CMContractOverallRetentionAmd::find($createdCMContractOverallRetentionAmd['id']), 'CMContractOverallRetentionAmd with given id must be in DB');
        $this->assertModelData($cMContractOverallRetentionAmd, $createdCMContractOverallRetentionAmd);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_overall_retention_amd()
    {
        $cMContractOverallRetentionAmd = CMContractOverallRetentionAmd::factory()->create();

        $dbCMContractOverallRetentionAmd = $this->cMContractOverallRetentionAmdRepo->find($cMContractOverallRetentionAmd->id);

        $dbCMContractOverallRetentionAmd = $dbCMContractOverallRetentionAmd->toArray();
        $this->assertModelData($cMContractOverallRetentionAmd->toArray(), $dbCMContractOverallRetentionAmd);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_overall_retention_amd()
    {
        $cMContractOverallRetentionAmd = CMContractOverallRetentionAmd::factory()->create();
        $fakeCMContractOverallRetentionAmd = CMContractOverallRetentionAmd::factory()->make()->toArray();

        $updatedCMContractOverallRetentionAmd = $this->cMContractOverallRetentionAmdRepo->update($fakeCMContractOverallRetentionAmd, $cMContractOverallRetentionAmd->id);

        $this->assertModelData($fakeCMContractOverallRetentionAmd, $updatedCMContractOverallRetentionAmd->toArray());
        $dbCMContractOverallRetentionAmd = $this->cMContractOverallRetentionAmdRepo->find($cMContractOverallRetentionAmd->id);
        $this->assertModelData($fakeCMContractOverallRetentionAmd, $dbCMContractOverallRetentionAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_overall_retention_amd()
    {
        $cMContractOverallRetentionAmd = CMContractOverallRetentionAmd::factory()->create();

        $resp = $this->cMContractOverallRetentionAmdRepo->delete($cMContractOverallRetentionAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractOverallRetentionAmd::find($cMContractOverallRetentionAmd->id), 'CMContractOverallRetentionAmd should not exist in DB');
    }
}
