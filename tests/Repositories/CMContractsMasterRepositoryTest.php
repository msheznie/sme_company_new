<?php namespace Tests\Repositories;

use App\Models\CMContractsMaster;
use App\Repositories\CMContractsMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMContractsMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMContractsMasterRepository
     */
    protected $cMContractsMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMContractsMasterRepo = \App::make(CMContractsMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_contracts_master()
    {
        $cMContractsMaster = CMContractsMaster::factory()->make()->toArray();

        $createdCMContractsMaster = $this->cMContractsMasterRepo->create($cMContractsMaster);

        $createdCMContractsMaster = $createdCMContractsMaster->toArray();
        $this->assertArrayHasKey('id', $createdCMContractsMaster);
        $this->assertNotNull($createdCMContractsMaster['id'], 'Created CMContractsMaster must have id specified');
        $this->assertNotNull(CMContractsMaster::find($createdCMContractsMaster['id']), 'CMContractsMaster with given id must be in DB');
        $this->assertModelData($cMContractsMaster, $createdCMContractsMaster);
    }

    /**
     * @test read
     */
    public function test_read_c_m_contracts_master()
    {
        $cMContractsMaster = CMContractsMaster::factory()->create();

        $dbCMContractsMaster = $this->cMContractsMasterRepo->find($cMContractsMaster->id);

        $dbCMContractsMaster = $dbCMContractsMaster->toArray();
        $this->assertModelData($cMContractsMaster->toArray(), $dbCMContractsMaster);
    }

    /**
     * @test update
     */
    public function test_update_c_m_contracts_master()
    {
        $cMContractsMaster = CMContractsMaster::factory()->create();
        $fakeCMContractsMaster = CMContractsMaster::factory()->make()->toArray();

        $updatedCMContractsMaster = $this->cMContractsMasterRepo->update($fakeCMContractsMaster, $cMContractsMaster->id);

        $this->assertModelData($fakeCMContractsMaster, $updatedCMContractsMaster->toArray());
        $dbCMContractsMaster = $this->cMContractsMasterRepo->find($cMContractsMaster->id);
        $this->assertModelData($fakeCMContractsMaster, $dbCMContractsMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_contracts_master()
    {
        $cMContractsMaster = CMContractsMaster::factory()->create();

        $resp = $this->cMContractsMasterRepo->delete($cMContractsMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(CMContractsMaster::find($cMContractsMaster->id), 'CMContractsMaster should not exist in DB');
    }
}
