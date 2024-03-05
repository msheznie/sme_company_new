<?php namespace Tests\Repositories;

use App\Models\CMContractSectionsMaster;
use App\Repositories\CMContractSectionsMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractSectionsMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractSectionsMasterRepository
     */
    protected $cMContractSectionsMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractSectionsMasterRepo = \App::make(CMContractSectionsMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contract_sections_master()
    {
        $cMContractSectionsMaster = CMContractSectionsMaster::factory()->make()->toArray();

        $createdCMContractSectionsMaster = $this->cMContractSectionsMasterRepo->create($cMContractSectionsMaster);

        $createdCMContractSectionsMaster = $createdCMContractSectionsMaster->toArray();
        $this->assertArrayHasKey('id', $createdCMContractSectionsMaster);
        $this->assertNotNull($createdCMContractSectionsMaster['id'], 'Created CMContractSectionsMaster must have id specified');
        $this->assertNotNull(CMContractSectionsMaster::find($createdCMContractSectionsMaster['id']), 'CMContractSectionsMaster with given id must be in DB');
        $this->assertModelData($cMContractSectionsMaster, $createdCMContractSectionsMaster);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contract_sections_master()
    {
        $cMContractSectionsMaster = CMContractSectionsMaster::factory()->create();

        $dbCMContractSectionsMaster = $this->cMContractSectionsMasterRepo->find($cMContractSectionsMaster->id);

        $dbCMContractSectionsMaster = $dbCMContractSectionsMaster->toArray();
        $this->assertModelData($cMContractSectionsMaster->toArray(), $dbCMContractSectionsMaster);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contract_sections_master()
    {
        $cMContractSectionsMaster = CMContractSectionsMaster::factory()->create();
        $fakeCMContractSectionsMaster = CMContractSectionsMaster::factory()->make()->toArray();

        $updatedCMContractSectionsMaster = $this->cMContractSectionsMasterRepo->update($fakeCMContractSectionsMaster, $cMContractSectionsMaster->id);

        $this->assertModelData($fakeCMContractSectionsMaster, $updatedCMContractSectionsMaster->toArray());
        $dbCMContractSectionsMaster = $this->cMContractSectionsMasterRepo->find($cMContractSectionsMaster->id);
        $this->assertModelData($fakeCMContractSectionsMaster, $dbCMContractSectionsMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contract_sections_master()
    {
        $cMContractSectionsMaster = CMContractSectionsMaster::factory()->create();

        $resp = $this->cMContractSectionsMasterRepo->delete($cMContractSectionsMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractSectionsMaster::find($cMContractSectionsMaster->id), 'CMContractSectionsMaster should not exist in DB');
    }
}
