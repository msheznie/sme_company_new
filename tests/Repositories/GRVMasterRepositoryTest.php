<?php namespace Tests\Repositories;

use App\Models\GRVMaster;
use App\Repositories\GRVMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class GRVMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var GRVMasterRepository
     */
    protected $gRVMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->gRVMasterRepo = \App::make(GRVMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_g_r_v_master()
    {
        $gRVMaster = GRVMaster::factory()->make()->toArray();

        $createdGRVMaster = $this->gRVMasterRepo->create($gRVMaster);

        $createdGRVMaster = $createdGRVMaster->toArray();
        $this->assertArrayHasKey('id', $createdGRVMaster);
        $this->assertNotNull($createdGRVMaster['id'], 'Created GRVMaster must have id specified');
        $this->assertNotNull(GRVMaster::find($createdGRVMaster['id']), 'GRVMaster with given id must be in DB');
        $this->assertModelData($gRVMaster, $createdGRVMaster);
    }

    /**
     * @test read
     */
    public function test_read_g_r_v_master()
    {
        $gRVMaster = GRVMaster::factory()->create();

        $dbGRVMaster = $this->gRVMasterRepo->find($gRVMaster->id);

        $dbGRVMaster = $dbGRVMaster->toArray();
        $this->assertModelData($gRVMaster->toArray(), $dbGRVMaster);
    }

    /**
     * @test update
     */
    public function test_update_g_r_v_master()
    {
        $gRVMaster = GRVMaster::factory()->create();
        $fakeGRVMaster = GRVMaster::factory()->make()->toArray();

        $updatedGRVMaster = $this->gRVMasterRepo->update($fakeGRVMaster, $gRVMaster->id);

        $this->assertModelData($fakeGRVMaster, $updatedGRVMaster->toArray());
        $dbGRVMaster = $this->gRVMasterRepo->find($gRVMaster->id);
        $this->assertModelData($fakeGRVMaster, $dbGRVMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_g_r_v_master()
    {
        $gRVMaster = GRVMaster::factory()->create();

        $resp = $this->gRVMasterRepo->delete($gRVMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(GRVMaster::find($gRVMaster->id), 'GRVMaster should not exist in DB');
    }
}
