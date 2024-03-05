<?php namespace Tests\Repositories;

use App\Models\CMIntentsMaster;
use App\Repositories\CMIntentsMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CMIntentsMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CMIntentsMasterRepository
     */
    protected $cMIntentsMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->cMIntentsMasterRepo = \App::make(CMIntentsMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_c_m_intents_master()
    {
        $cMIntentsMaster = CMIntentsMaster::factory()->make()->toArray();

        $createdCMIntentsMaster = $this->cMIntentsMasterRepo->create($cMIntentsMaster);

        $createdCMIntentsMaster = $createdCMIntentsMaster->toArray();
        $this->assertArrayHasKey('id', $createdCMIntentsMaster);
        $this->assertNotNull($createdCMIntentsMaster['id'], 'Created CMIntentsMaster must have id specified');
        $this->assertNotNull(CMIntentsMaster::find($createdCMIntentsMaster['id']), 'CMIntentsMaster with given id must be in DB');
        $this->assertModelData($cMIntentsMaster, $createdCMIntentsMaster);
    }

    /**
     * @test read
     */
    public function test_read_c_m_intents_master()
    {
        $cMIntentsMaster = CMIntentsMaster::factory()->create();

        $dbCMIntentsMaster = $this->cMIntentsMasterRepo->find($cMIntentsMaster->id);

        $dbCMIntentsMaster = $dbCMIntentsMaster->toArray();
        $this->assertModelData($cMIntentsMaster->toArray(), $dbCMIntentsMaster);
    }

    /**
     * @test update
     */
    public function test_update_c_m_intents_master()
    {
        $cMIntentsMaster = CMIntentsMaster::factory()->create();
        $fakeCMIntentsMaster = CMIntentsMaster::factory()->make()->toArray();

        $updatedCMIntentsMaster = $this->cMIntentsMasterRepo->update($fakeCMIntentsMaster, $cMIntentsMaster->id);

        $this->assertModelData($fakeCMIntentsMaster, $updatedCMIntentsMaster->toArray());
        $dbCMIntentsMaster = $this->cMIntentsMasterRepo->find($cMIntentsMaster->id);
        $this->assertModelData($fakeCMIntentsMaster, $dbCMIntentsMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_c_m_intents_master()
    {
        $cMIntentsMaster = CMIntentsMaster::factory()->create();

        $resp = $this->cMIntentsMasterRepo->delete($cMIntentsMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(CMIntentsMaster::find($cMIntentsMaster->id), 'CMIntentsMaster should not exist in DB');
    }
}
