<?php namespace Tests\Repositories;

use App\Models\CMPartiesMaster;
use App\Repositories\CMPartiesMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMPartiesMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMPartiesMasterRepository
     */
    protected $cMPartiesMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMPartiesMasterRepo = \App::make(CMPartiesMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_parties_master()
    {
        $cMPartiesMaster = CMPartiesMaster::factory()->make()->toArray();

        $createdCMPartiesMaster = $this->cMPartiesMasterRepo->create($cMPartiesMaster);

        $createdCMPartiesMaster = $createdCMPartiesMaster->toArray();
        $this->assertArrayHasKey('id', $createdCMPartiesMaster);
        $this->assertNotNull($createdCMPartiesMaster['id'], 'Created CMPartiesMaster must have id specified');
        $this->assertNotNull(CMPartiesMaster::find($createdCMPartiesMaster['id']), 'CMPartiesMaster with given id must be in DB');
        $this->assertModelData($cMPartiesMaster, $createdCMPartiesMaster);
    }

    /**
     * @test read
     */
    public function test_read_c_m_parties_master()
    {
        $cMPartiesMaster = CMPartiesMaster::factory()->create();

        $dbCMPartiesMaster = $this->cMPartiesMasterRepo->find($cMPartiesMaster->id);

        $dbCMPartiesMaster = $dbCMPartiesMaster->toArray();
        $this->assertModelData($cMPartiesMaster->toArray(), $dbCMPartiesMaster);
    }

    /**
     * @test update
     */
    public function test_update_c_m_parties_master()
    {
        $cMPartiesMaster = CMPartiesMaster::factory()->create();
        $fakeCMPartiesMaster = CMPartiesMaster::factory()->make()->toArray();

        $updatedCMPartiesMaster = $this->cMPartiesMasterRepo->update($fakeCMPartiesMaster, $cMPartiesMaster->id);

        $this->assertModelData($fakeCMPartiesMaster, $updatedCMPartiesMaster->toArray());
        $dbCMPartiesMaster = $this->cMPartiesMasterRepo->find($cMPartiesMaster->id);
        $this->assertModelData($fakeCMPartiesMaster, $dbCMPartiesMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_parties_master()
    {
        $cMPartiesMaster = CMPartiesMaster::factory()->create();

        $resp = $this->cMPartiesMasterRepo->delete($cMPartiesMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(CMPartiesMaster::find($cMPartiesMaster->id), 'CMPartiesMaster should not exist in DB');
    }
}
