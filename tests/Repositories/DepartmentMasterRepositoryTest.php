<?php namespace Tests\Repositories;

use App\Models\DepartmentMaster;
use App\Repositories\DepartmentMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DepartmentMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DepartmentMasterRepository
     */
    protected $departmentMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->departmentMasterRepo = \App::make(DepartmentMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_department_master()
    {
        $departmentMaster = DepartmentMaster::factory()->make()->toArray();

        $createdDepartmentMaster = $this->departmentMasterRepo->create($departmentMaster);

        $createdDepartmentMaster = $createdDepartmentMaster->toArray();
        $this->assertArrayHasKey('id', $createdDepartmentMaster);
        $this->assertNotNull($createdDepartmentMaster['id'], 'Created DepartmentMaster must have id specified');
        $this->assertNotNull(DepartmentMaster::find($createdDepartmentMaster['id']), 'DepartmentMaster with given id must be in DB');
        $this->assertModelData($departmentMaster, $createdDepartmentMaster);
    }

    /**
     * @test read
     */
    public function test_read_department_master()
    {
        $departmentMaster = DepartmentMaster::factory()->create();

        $dbDepartmentMaster = $this->departmentMasterRepo->find($departmentMaster->id);

        $dbDepartmentMaster = $dbDepartmentMaster->toArray();
        $this->assertModelData($departmentMaster->toArray(), $dbDepartmentMaster);
    }

    /**
     * @test update
     */
    public function test_update_department_master()
    {
        $departmentMaster = DepartmentMaster::factory()->create();
        $fakeDepartmentMaster = DepartmentMaster::factory()->make()->toArray();

        $updatedDepartmentMaster = $this->departmentMasterRepo->update($fakeDepartmentMaster, $departmentMaster->id);

        $this->assertModelData($fakeDepartmentMaster, $updatedDepartmentMaster->toArray());
        $dbDepartmentMaster = $this->departmentMasterRepo->find($departmentMaster->id);
        $this->assertModelData($fakeDepartmentMaster, $dbDepartmentMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_department_master()
    {
        $departmentMaster = DepartmentMaster::factory()->create();

        $resp = $this->departmentMasterRepo->delete($departmentMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(DepartmentMaster::find($departmentMaster->id), 'DepartmentMaster should not exist in DB');
    }
}
