<?php namespace Tests\Repositories;

use App\Models\CMContractUserAssignAmd;
use App\Repositories\CMContractUserAssignAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractUserAssignAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractUserAssignAmdRepository
     */
    protected $cMContractUserAssignAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractUserAssignAmdRepo = \App::make(CMContractUserAssignAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_user_assign_amd()
    {
        $cMContractUserAssignAmd = CMContractUserAssignAmd::factory()->make()->toArray();

        $createdCMContractUserAssignAmd = $this->cMContractUserAssignAmdRepo->create($cMContractUserAssignAmd);

        $createdCMContractUserAssignAmd = $createdCMContractUserAssignAmd->toArray();
        $this->assertArrayHasKey('id', $createdCMContractUserAssignAmd);
        $this->assertNotNull($createdCMContractUserAssignAmd['id'], 'Created CMContractUserAssignAmd must have id specified');
        $this->assertNotNull(CMContractUserAssignAmd::find($createdCMContractUserAssignAmd['id']), 'CMContractUserAssignAmd with given id must be in DB');
        $this->assertModelData($cMContractUserAssignAmd, $createdCMContractUserAssignAmd);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_user_assign_amd()
    {
        $cMContractUserAssignAmd = CMContractUserAssignAmd::factory()->create();

        $dbCMContractUserAssignAmd = $this->cMContractUserAssignAmdRepo->find($cMContractUserAssignAmd->id);

        $dbCMContractUserAssignAmd = $dbCMContractUserAssignAmd->toArray();
        $this->assertModelData($cMContractUserAssignAmd->toArray(), $dbCMContractUserAssignAmd);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_user_assign_amd()
    {
        $cMContractUserAssignAmd = CMContractUserAssignAmd::factory()->create();
        $fakeCMContractUserAssignAmd = CMContractUserAssignAmd::factory()->make()->toArray();

        $updatedCMContractUserAssignAmd = $this->cMContractUserAssignAmdRepo->update($fakeCMContractUserAssignAmd, $cMContractUserAssignAmd->id);

        $this->assertModelData($fakeCMContractUserAssignAmd, $updatedCMContractUserAssignAmd->toArray());
        $dbCMContractUserAssignAmd = $this->cMContractUserAssignAmdRepo->find($cMContractUserAssignAmd->id);
        $this->assertModelData($fakeCMContractUserAssignAmd, $dbCMContractUserAssignAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_user_assign_amd()
    {
        $cMContractUserAssignAmd = CMContractUserAssignAmd::factory()->create();

        $resp = $this->cMContractUserAssignAmdRepo->delete($cMContractUserAssignAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractUserAssignAmd::find($cMContractUserAssignAmd->id), 'CMContractUserAssignAmd should not exist in DB');
    }
}
