<?php namespace Tests\Repositories;

use App\Models\Employees;
use App\Repositories\EmployeesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class EmployeesRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var EmployeesRepository
     */
    protected $employeesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->employeesRepo = \App::make(EmployeesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_employees()
    {
        $employees = Employees::factory()->make()->toArray();

        $createdEmployees = $this->employeesRepo->create($employees);

        $createdEmployees = $createdEmployees->toArray();
        $this->assertArrayHasKey('id', $createdEmployees);
        $this->assertNotNull($createdEmployees['id'], 'Created Employees must have id specified');
        $this->assertNotNull(Employees::find($createdEmployees['id']), 'Employees with given id must be in DB');
        $this->assertModelData($employees, $createdEmployees);
    }

    /**
     * @test read
     */
    public function test_read_employees()
    {
        $employees = Employees::factory()->create();

        $dbEmployees = $this->employeesRepo->find($employees->id);

        $dbEmployees = $dbEmployees->toArray();
        $this->assertModelData($employees->toArray(), $dbEmployees);
    }

    /**
     * @test update
     */
    public function test_update_employees()
    {
        $employees = Employees::factory()->create();
        $fakeEmployees = Employees::factory()->make()->toArray();

        $updatedEmployees = $this->employeesRepo->update($fakeEmployees, $employees->id);

        $this->assertModelData($fakeEmployees, $updatedEmployees->toArray());
        $dbEmployees = $this->employeesRepo->find($employees->id);
        $this->assertModelData($fakeEmployees, $dbEmployees->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_employees()
    {
        $employees = Employees::factory()->create();

        $resp = $this->employeesRepo->delete($employees->id);

        $this->assertTrue($resp);
        $this->assertNull(Employees::find($employees->id), 'Employees should not exist in DB');
    }
}
