<?php namespace Tests\Repositories;

use App\Models\CMContractStatusHistoryAmd;
use App\Repositories\CMContractStatusHistoryAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractStatusHistoryAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractStatusHistoryAmdRepository
     */
    protected $cMContractStatusHistoryAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractStatusHistoryAmdRepo = \App::make(CMContractStatusHistoryAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_status_history_amd()
    {
        $cMContractStatusHistoryAmd = CMContractStatusHistoryAmd::factory()->make()->toArray();

        $createdCMContractStatusHistoryAmd = $this->cMContractStatusHistoryAmdRepo->create($cMContractStatusHistoryAmd);

        $createdCMContractStatusHistoryAmd = $createdCMContractStatusHistoryAmd->toArray();
        $this->assertArrayHasKey('id', $createdCMContractStatusHistoryAmd);
        $this->assertNotNull($createdCMContractStatusHistoryAmd['id'], 'Created CMContractStatusHistoryAmd must have id specified');
        $this->assertNotNull(CMContractStatusHistoryAmd::find($createdCMContractStatusHistoryAmd['id']), 'CMContractStatusHistoryAmd with given id must be in DB');
        $this->assertModelData($cMContractStatusHistoryAmd, $createdCMContractStatusHistoryAmd);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_status_history_amd()
    {
        $cMContractStatusHistoryAmd = CMContractStatusHistoryAmd::factory()->create();

        $dbCMContractStatusHistoryAmd = $this->cMContractStatusHistoryAmdRepo->find($cMContractStatusHistoryAmd->id);

        $dbCMContractStatusHistoryAmd = $dbCMContractStatusHistoryAmd->toArray();
        $this->assertModelData($cMContractStatusHistoryAmd->toArray(), $dbCMContractStatusHistoryAmd);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_status_history_amd()
    {
        $cMContractStatusHistoryAmd = CMContractStatusHistoryAmd::factory()->create();
        $fakeCMContractStatusHistoryAmd = CMContractStatusHistoryAmd::factory()->make()->toArray();

        $updatedCMContractStatusHistoryAmd = $this->cMContractStatusHistoryAmdRepo->update($fakeCMContractStatusHistoryAmd, $cMContractStatusHistoryAmd->id);

        $this->assertModelData($fakeCMContractStatusHistoryAmd, $updatedCMContractStatusHistoryAmd->toArray());
        $dbCMContractStatusHistoryAmd = $this->cMContractStatusHistoryAmdRepo->find($cMContractStatusHistoryAmd->id);
        $this->assertModelData($fakeCMContractStatusHistoryAmd, $dbCMContractStatusHistoryAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_status_history_amd()
    {
        $cMContractStatusHistoryAmd = CMContractStatusHistoryAmd::factory()->create();

        $resp = $this->cMContractStatusHistoryAmdRepo->delete($cMContractStatusHistoryAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractStatusHistoryAmd::find($cMContractStatusHistoryAmd->id), 'CMContractStatusHistoryAmd should not exist in DB');
    }
}
