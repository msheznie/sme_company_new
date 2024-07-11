<?php namespace Tests\Repositories;

use App\Models\CMContractMileStoneAmd;
use App\Repositories\CMContractMileStoneAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractMileStoneAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractMileStoneAmdRepository
     */
    protected $cMContractMileStoneAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractMileStoneAmdRepo = \App::make(CMContractMileStoneAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_mile_stone_amd()
    {
        $cMContractMileStoneAmd = CMContractMileStoneAmd::factory()->make()->toArray();

        $createdCMContractMileStoneAmd = $this->cMContractMileStoneAmdRepo->create($cMContractMileStoneAmd);

        $createdCMContractMileStoneAmd = $createdCMContractMileStoneAmd->toArray();
        $this->assertArrayHasKey('id', $createdCMContractMileStoneAmd);
        $this->assertNotNull($createdCMContractMileStoneAmd['id'], 'Created CMContractMileStoneAmd must have id specified');
        $this->assertNotNull(CMContractMileStoneAmd::find($createdCMContractMileStoneAmd['id']), 'CMContractMileStoneAmd with given id must be in DB');
        $this->assertModelData($cMContractMileStoneAmd, $createdCMContractMileStoneAmd);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_mile_stone_amd()
    {
        $cMContractMileStoneAmd = CMContractMileStoneAmd::factory()->create();

        $dbCMContractMileStoneAmd = $this->cMContractMileStoneAmdRepo->find($cMContractMileStoneAmd->id);

        $dbCMContractMileStoneAmd = $dbCMContractMileStoneAmd->toArray();
        $this->assertModelData($cMContractMileStoneAmd->toArray(), $dbCMContractMileStoneAmd);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_mile_stone_amd()
    {
        $cMContractMileStoneAmd = CMContractMileStoneAmd::factory()->create();
        $fakeCMContractMileStoneAmd = CMContractMileStoneAmd::factory()->make()->toArray();

        $updatedCMContractMileStoneAmd = $this->cMContractMileStoneAmdRepo->update($fakeCMContractMileStoneAmd, $cMContractMileStoneAmd->id);

        $this->assertModelData($fakeCMContractMileStoneAmd, $updatedCMContractMileStoneAmd->toArray());
        $dbCMContractMileStoneAmd = $this->cMContractMileStoneAmdRepo->find($cMContractMileStoneAmd->id);
        $this->assertModelData($fakeCMContractMileStoneAmd, $dbCMContractMileStoneAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_mile_stone_amd()
    {
        $cMContractMileStoneAmd = CMContractMileStoneAmd::factory()->create();

        $resp = $this->cMContractMileStoneAmdRepo->delete($cMContractMileStoneAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractMileStoneAmd::find($cMContractMileStoneAmd->id), 'CMContractMileStoneAmd should not exist in DB');
    }
}
