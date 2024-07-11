<?php namespace Tests\Repositories;

use App\Models\CMContractBoqItemsAmd;
use App\Repositories\CMContractBoqItemsAmdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractBoqItemsAmdRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractBoqItemsAmdRepository
     */
    protected $cMContractBoqItemsAmdRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractBoqItemsAmdRepo = \App::make(CMContractBoqItemsAmdRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_boq_items_amd()
    {
        $cMContractBoqItemsAmd = CMContractBoqItemsAmd::factory()->make()->toArray();

        $createdCMContractBoqItemsAmd = $this->cMContractBoqItemsAmdRepo->create($cMContractBoqItemsAmd);

        $createdCMContractBoqItemsAmd = $createdCMContractBoqItemsAmd->toArray();
        $this->assertArrayHasKey('id', $createdCMContractBoqItemsAmd);
        $this->assertNotNull($createdCMContractBoqItemsAmd['id'], 'Created CMContractBoqItemsAmd must have id specified');
        $this->assertNotNull(CMContractBoqItemsAmd::find($createdCMContractBoqItemsAmd['id']), 'CMContractBoqItemsAmd with given id must be in DB');
        $this->assertModelData($cMContractBoqItemsAmd, $createdCMContractBoqItemsAmd);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_boq_items_amd()
    {
        $cMContractBoqItemsAmd = CMContractBoqItemsAmd::factory()->create();

        $dbCMContractBoqItemsAmd = $this->cMContractBoqItemsAmdRepo->find($cMContractBoqItemsAmd->id);

        $dbCMContractBoqItemsAmd = $dbCMContractBoqItemsAmd->toArray();
        $this->assertModelData($cMContractBoqItemsAmd->toArray(), $dbCMContractBoqItemsAmd);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_boq_items_amd()
    {
        $cMContractBoqItemsAmd = CMContractBoqItemsAmd::factory()->create();
        $fakeCMContractBoqItemsAmd = CMContractBoqItemsAmd::factory()->make()->toArray();

        $updatedCMContractBoqItemsAmd = $this->cMContractBoqItemsAmdRepo->update($fakeCMContractBoqItemsAmd, $cMContractBoqItemsAmd->id);

        $this->assertModelData($fakeCMContractBoqItemsAmd, $updatedCMContractBoqItemsAmd->toArray());
        $dbCMContractBoqItemsAmd = $this->cMContractBoqItemsAmdRepo->find($cMContractBoqItemsAmd->id);
        $this->assertModelData($fakeCMContractBoqItemsAmd, $dbCMContractBoqItemsAmd->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_boq_items_amd()
    {
        $cMContractBoqItemsAmd = CMContractBoqItemsAmd::factory()->create();

        $resp = $this->cMContractBoqItemsAmdRepo->delete($cMContractBoqItemsAmd->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractBoqItemsAmd::find($cMContractBoqItemsAmd->id), 'CMContractBoqItemsAmd should not exist in DB');
    }
}
