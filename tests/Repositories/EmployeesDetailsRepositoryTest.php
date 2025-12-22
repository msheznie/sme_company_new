<?php namespace Tests\Repositories;

use App\Models\EmployeesDetails;
use App\Repositories\EmployeesDetailsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class EmployeesDetailsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var EmployeesDetailsRepository
     */
    protected $employeesDetailsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->employeesDetailsRepo = \App::make(EmployeesDetailsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_employees_details()
    {
        $employeesDetails = EmployeesDetails::factory()->make()->toArray();

        $createdEmployeesDetails = $this->employeesDetailsRepo->create($employeesDetails);

        $createdEmployeesDetails = $createdEmployeesDetails->toArray();
        $this->assertArrayHasKey('id', $createdEmployeesDetails);
        $this->assertNotNull($createdEmployeesDetails['id'], 'Created EmployeesDetails must have id specified');
        $this->assertNotNull(EmployeesDetails::find($createdEmployeesDetails['id']), 'EmployeesDetails with given id must be in DB');
        $this->assertModelData($employeesDetails, $createdEmployeesDetails);
    }

    /**
     * @test read
     */
    public function test_read_employees_details()
    {
        $employeesDetails = EmployeesDetails::factory()->create();

        $dbEmployeesDetails = $this->employeesDetailsRepo->find($employeesDetails->id);

        $dbEmployeesDetails = $dbEmployeesDetails->toArray();
        $this->assertModelData($employeesDetails->toArray(), $dbEmployeesDetails);
    }

    /**
     * @test update
     */
    public function test_update_employees_details()
    {
        $employeesDetails = EmployeesDetails::factory()->create();
        $fakeEmployeesDetails = EmployeesDetails::factory()->make()->toArray();

        $updatedEmployeesDetails = $this->employeesDetailsRepo->update($fakeEmployeesDetails, $employeesDetails->id);

        $this->assertModelData($fakeEmployeesDetails, $updatedEmployeesDetails->toArray());
        $dbEmployeesDetails = $this->employeesDetailsRepo->find($employeesDetails->id);
        $this->assertModelData($fakeEmployeesDetails, $dbEmployeesDetails->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_employees_details()
    {
        $employeesDetails = EmployeesDetails::factory()->create();

        $resp = $this->employeesDetailsRepo->delete($employeesDetails->id);

        $this->assertTrue($resp);
        $this->assertNull(EmployeesDetails::find($employeesDetails->id), 'EmployeesDetails should not exist in DB');
    }
}
