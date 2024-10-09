<?php namespace Tests\Repositories;

use App\Models\EmployeeDepartments;
use App\Repositories\EmployeeDepartmentsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class EmployeeDepartmentsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var EmployeeDepartmentsRepository
     */
    protected $employeeDepartmentsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->employeeDepartmentsRepo = \App::make(EmployeeDepartmentsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_employee_departments()
    {
        $employeeDepartments = EmployeeDepartments::factory()->make()->toArray();

        $createdEmployeeDepartments = $this->employeeDepartmentsRepo->create($employeeDepartments);

        $createdEmployeeDepartments = $createdEmployeeDepartments->toArray();
        $this->assertArrayHasKey('id', $createdEmployeeDepartments);
        $this->assertNotNull($createdEmployeeDepartments['id'], 'Created EmployeeDepartments must have id specified');
        $this->assertNotNull(EmployeeDepartments::find($createdEmployeeDepartments['id']), 'EmployeeDepartments with given id must be in DB');
        $this->assertModelData($employeeDepartments, $createdEmployeeDepartments);
    }

    /**
     * @test read
     */
    public function test_read_employee_departments()
    {
        $employeeDepartments = EmployeeDepartments::factory()->create();

        $dbEmployeeDepartments = $this->employeeDepartmentsRepo->find($employeeDepartments->id);

        $dbEmployeeDepartments = $dbEmployeeDepartments->toArray();
        $this->assertModelData($employeeDepartments->toArray(), $dbEmployeeDepartments);
    }

    /**
     * @test update
     */
    public function test_update_employee_departments()
    {
        $employeeDepartments = EmployeeDepartments::factory()->create();
        $fakeEmployeeDepartments = EmployeeDepartments::factory()->make()->toArray();

        $updatedEmployeeDepartments = $this->employeeDepartmentsRepo->update($fakeEmployeeDepartments, $employeeDepartments->id);

        $this->assertModelData($fakeEmployeeDepartments, $updatedEmployeeDepartments->toArray());
        $dbEmployeeDepartments = $this->employeeDepartmentsRepo->find($employeeDepartments->id);
        $this->assertModelData($fakeEmployeeDepartments, $dbEmployeeDepartments->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_employee_departments()
    {
        $employeeDepartments = EmployeeDepartments::factory()->create();

        $resp = $this->employeeDepartmentsRepo->delete($employeeDepartments->id);

        $this->assertTrue($resp);
        $this->assertNull(EmployeeDepartments::find($employeeDepartments->id), 'EmployeeDepartments should not exist in DB');
    }
}
