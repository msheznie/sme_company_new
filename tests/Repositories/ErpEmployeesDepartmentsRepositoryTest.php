<?php namespace Tests\Repositories;

use App\Models\ErpEmployeesDepartments;
use App\Repositories\ErpEmployeesDepartmentsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ErpEmployeesDepartmentsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ErpEmployeesDepartmentsRepository
     */
    protected $erpEmployeesDepartmentsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->erpEmployeesDepartmentsRepo = \App::make(ErpEmployeesDepartmentsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_erp_employees_departments()
    {
        $erpEmployeesDepartments = ErpEmployeesDepartments::factory()->make()->toArray();

        $createdErpEmployeesDepartments = $this->erpEmployeesDepartmentsRepo->create($erpEmployeesDepartments);

        $createdErpEmployeesDepartments = $createdErpEmployeesDepartments->toArray();
        $this->assertArrayHasKey('id', $createdErpEmployeesDepartments);
        $this->assertNotNull($createdErpEmployeesDepartments['id'],
            'Created ErpEmployeesDepartments must have id specified');
        $this->assertNotNull(ErpEmployeesDepartments::find($createdErpEmployeesDepartments['id']),
            'ErpEmployeesDepartments with given id must be in DB');
        $this->assertModelData($erpEmployeesDepartments, $createdErpEmployeesDepartments);
    }

    /**
     * @test read
     */
    public function test_read_erp_employees_departments()
    {
        $erpEmployeesDepartments = ErpEmployeesDepartments::factory()->create();

        $dbErpEmployeesDepartments = $this->erpEmployeesDepartmentsRepo->find($erpEmployeesDepartments->id);

        $dbErpEmployeesDepartments = $dbErpEmployeesDepartments->toArray();
        $this->assertModelData($erpEmployeesDepartments->toArray(), $dbErpEmployeesDepartments);
    }

    /**
     * @test update
     */
    public function test_update_erp_employees_departments()
    {
        $erpEmployeesDepartments = ErpEmployeesDepartments::factory()->create();
        $fakeErpEmployeesDepartments = ErpEmployeesDepartments::factory()->make()->toArray();

        $updatedErpEmployeesDepartments = $this->erpEmployeesDepartmentsRepo->update($fakeErpEmployeesDepartments,
            $erpEmployeesDepartments->id);

        $this->assertModelData($fakeErpEmployeesDepartments, $updatedErpEmployeesDepartments->toArray());
        $dbErpEmployeesDepartments = $this->erpEmployeesDepartmentsRepo->find($erpEmployeesDepartments->id);
        $this->assertModelData($fakeErpEmployeesDepartments, $dbErpEmployeesDepartments->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_erp_employees_departments()
    {
        $erpEmployeesDepartments = ErpEmployeesDepartments::factory()->create();

        $resp = $this->erpEmployeesDepartmentsRepo->delete($erpEmployeesDepartments->id);

        $this->assertTrue($resp);
        $this->assertNull(ErpEmployeesDepartments::find($erpEmployeesDepartments->id),
            'ErpEmployeesDepartments should not exist in DB');
    }
}
