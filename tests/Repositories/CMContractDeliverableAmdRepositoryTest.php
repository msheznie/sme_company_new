<?php namespace Tests\Repositories;

use App\Models\CMContractDeliverableAmd;
use App\Repositories\CMContractDeliverableAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractDeliverableAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractDeliverableAmdRepository
     */
    protected $cMContractDeliverableAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractDeliverableAmdRepo = \App::make(CMContractDeliverableAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_deliverable_amd()
    {
        $cMContractDeliverableAmd = CMContractDeliverableAmd::factory()->make()->toArray();

        $createdCMContractDeliverableAmd = $this->cMContractDeliverableAmdRepo->create($cMContractDeliverableAmd);

        $createdCMContractDeliverableAmd = $createdCMContractDeliverableAmd->toArray();
        $this->assertArrayHasKey('id', $createdCMContractDeliverableAmd);
        $this->assertNotNull($createdCMContractDeliverableAmd['id'], 'Created CMContractDeliverableAmd must have id specified');
        $this->assertNotNull(CMContractDeliverableAmd::find($createdCMContractDeliverableAmd['id']), 'CMContractDeliverableAmd with given id must be in DB');
        $this->assertModelData($cMContractDeliverableAmd, $createdCMContractDeliverableAmd);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_deliverable_amd()
    {
        $cMContractDeliverableAmd = CMContractDeliverableAmd::factory()->create();

        $dbCMContractDeliverableAmd = $this->cMContractDeliverableAmdRepo->find($cMContractDeliverableAmd->id);

        $dbCMContractDeliverableAmd = $dbCMContractDeliverableAmd->toArray();
        $this->assertModelData($cMContractDeliverableAmd->toArray(), $dbCMContractDeliverableAmd);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_deliverable_amd()
    {
        $cMContractDeliverableAmd = CMContractDeliverableAmd::factory()->create();
        $fakeCMContractDeliverableAmd = CMContractDeliverableAmd::factory()->make()->toArray();

        $updatedCMContractDeliverableAmd = $this->cMContractDeliverableAmdRepo->update($fakeCMContractDeliverableAmd, $cMContractDeliverableAmd->id);

        $this->assertModelData($fakeCMContractDeliverableAmd, $updatedCMContractDeliverableAmd->toArray());
        $dbCMContractDeliverableAmd = $this->cMContractDeliverableAmdRepo->find($cMContractDeliverableAmd->id);
        $this->assertModelData($fakeCMContractDeliverableAmd, $dbCMContractDeliverableAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_deliverable_amd()
    {
        $cMContractDeliverableAmd = CMContractDeliverableAmd::factory()->create();

        $resp = $this->cMContractDeliverableAmdRepo->delete($cMContractDeliverableAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractDeliverableAmd::find($cMContractDeliverableAmd->id), 'CMContractDeliverableAmd should not exist in DB');
    }
}
