<?php namespace Tests\Repositories;

use App\Models\CMCounterPartiesMaster;
use App\Repositories\CMCounterPartiesMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMCounterPartiesMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMCounterPartiesMasterRepository
     */
    protected $cMCounterPartiesMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMCounterPartiesMasterRepo = \App::make(CMCounterPartiesMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_counter_parties_master()
    {
        $cMCounterPartiesMaster = CMCounterPartiesMaster::factory()->make()->toArray();

        $createdCMCounterPartiesMaster = $this->cMCounterPartiesMasterRepo->create($cMCounterPartiesMaster);

        $createdCMCounterPartiesMaster = $createdCMCounterPartiesMaster->toArray();
        $this->assertArrayHasKey('id', $createdCMCounterPartiesMaster);
        $this->assertNotNull($createdCMCounterPartiesMaster['id'], 'Created CMCounterPartiesMaster must have id specified');
        $this->assertNotNull(CMCounterPartiesMaster::find($createdCMCounterPartiesMaster['id']), 'CMCounterPartiesMaster with given id must be in DB');
        $this->assertModelData($cMCounterPartiesMaster, $createdCMCounterPartiesMaster);
    }

    /**
     * @test read
     */
    public function test_read_c_m_counter_parties_master()
    {
        $cMCounterPartiesMaster = CMCounterPartiesMaster::factory()->create();

        $dbCMCounterPartiesMaster = $this->cMCounterPartiesMasterRepo->find($cMCounterPartiesMaster->id);

        $dbCMCounterPartiesMaster = $dbCMCounterPartiesMaster->toArray();
        $this->assertModelData($cMCounterPartiesMaster->toArray(), $dbCMCounterPartiesMaster);
    }

    /**
     * @test update
     */
    public function test_update_c_m_counter_parties_master()
    {
        $cMCounterPartiesMaster = CMCounterPartiesMaster::factory()->create();
        $fakeCMCounterPartiesMaster = CMCounterPartiesMaster::factory()->make()->toArray();

        $updatedCMCounterPartiesMaster = $this->cMCounterPartiesMasterRepo->update($fakeCMCounterPartiesMaster, $cMCounterPartiesMaster->id);

        $this->assertModelData($fakeCMCounterPartiesMaster, $updatedCMCounterPartiesMaster->toArray());
        $dbCMCounterPartiesMaster = $this->cMCounterPartiesMasterRepo->find($cMCounterPartiesMaster->id);
        $this->assertModelData($fakeCMCounterPartiesMaster, $dbCMCounterPartiesMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_counter_parties_master()
    {
        $cMCounterPartiesMaster = CMCounterPartiesMaster::factory()->create();

        $resp = $this->cMCounterPartiesMasterRepo->delete($cMCounterPartiesMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(CMCounterPartiesMaster::find($cMCounterPartiesMaster->id), 'CMCounterPartiesMaster should not exist in DB');
    }
}
