<?php namespace Tests\Repositories;

use App\Models\EmployeesLanguage;
use App\Repositories\EmployeesLanguageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class EmployeesLanguageRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var EmployeesLanguageRepository
     */
    protected $employeesLanguageRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->employeesLanguageRepo = \App::make(EmployeesLanguageRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_employees_language()
    {
        $employeesLanguage = EmployeesLanguage::factory()->make()->toArray();

        $createdEmployeesLanguage = $this->employeesLanguageRepo->create($employeesLanguage);

        $createdEmployeesLanguage = $createdEmployeesLanguage->toArray();
        $this->assertArrayHasKey('id', $createdEmployeesLanguage);
        $this->assertNotNull($createdEmployeesLanguage['id'], 'Created EmployeesLanguage must have id specified');
        $this->assertNotNull(EmployeesLanguage::find($createdEmployeesLanguage['id']), 'EmployeesLanguage with given id must be in DB');
        $this->assertModelData($employeesLanguage, $createdEmployeesLanguage);
    }

    /**
     * @test read
     */
    public function test_read_employees_language()
    {
        $employeesLanguage = EmployeesLanguage::factory()->create();

        $dbEmployeesLanguage = $this->employeesLanguageRepo->find($employeesLanguage->id);

        $dbEmployeesLanguage = $dbEmployeesLanguage->toArray();
        $this->assertModelData($employeesLanguage->toArray(), $dbEmployeesLanguage);
    }

    /**
     * @test update
     */
    public function test_update_employees_language()
    {
        $employeesLanguage = EmployeesLanguage::factory()->create();
        $fakeEmployeesLanguage = EmployeesLanguage::factory()->make()->toArray();

        $updatedEmployeesLanguage = $this->employeesLanguageRepo->update($fakeEmployeesLanguage, $employeesLanguage->id);

        $this->assertModelData($fakeEmployeesLanguage, $updatedEmployeesLanguage->toArray());
        $dbEmployeesLanguage = $this->employeesLanguageRepo->find($employeesLanguage->id);
        $this->assertModelData($fakeEmployeesLanguage, $dbEmployeesLanguage->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_employees_language()
    {
        $employeesLanguage = EmployeesLanguage::factory()->create();

        $resp = $this->employeesLanguageRepo->delete($employeesLanguage->id);

        $this->assertTrue($resp);
        $this->assertNull(EmployeesLanguage::find($employeesLanguage->id), 'EmployeesLanguage should not exist in DB');
    }
}
