<?php namespace Tests\Repositories;

use App\Models\ErpEmployeeNavigation;
use App\Repositories\ErpEmployeeNavigationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ErpEmployeeNavigationRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ErpEmployeeNavigationRepository
     */
    protected $erpEmployeeNavigationRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->erpEmployeeNavigationRepo = \App::make(ErpEmployeeNavigationRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_erp_employee_navigation()
    {
        $erpEmployeeNavigation = ErpEmployeeNavigation::factory()->make()->toArray();

        $createdErpEmployeeNavigation = $this->erpEmployeeNavigationRepo->create($erpEmployeeNavigation);

        $createdErpEmployeeNavigation = $createdErpEmployeeNavigation->toArray();
        $this->assertArrayHasKey('id', $createdErpEmployeeNavigation);
        $this->assertNotNull($createdErpEmployeeNavigation['id'], 'Created ErpEmployeeNavigation must have id specified');
        $this->assertNotNull(ErpEmployeeNavigation::find($createdErpEmployeeNavigation['id']), 'ErpEmployeeNavigation with given id must be in DB');
        $this->assertModelData($erpEmployeeNavigation, $createdErpEmployeeNavigation);
    }

    /**
     * @test read
     */
    public function test_read_erp_employee_navigation()
    {
        $erpEmployeeNavigation = ErpEmployeeNavigation::factory()->create();

        $dbErpEmployeeNavigation = $this->erpEmployeeNavigationRepo->find($erpEmployeeNavigation->id);

        $dbErpEmployeeNavigation = $dbErpEmployeeNavigation->toArray();
        $this->assertModelData($erpEmployeeNavigation->toArray(), $dbErpEmployeeNavigation);
    }

    /**
     * @test update
     */
    public function test_update_erp_employee_navigation()
    {
        $erpEmployeeNavigation = ErpEmployeeNavigation::factory()->create();
        $fakeErpEmployeeNavigation = ErpEmployeeNavigation::factory()->make()->toArray();

        $updatedErpEmployeeNavigation = $this->erpEmployeeNavigationRepo->update($fakeErpEmployeeNavigation, $erpEmployeeNavigation->id);

        $this->assertModelData($fakeErpEmployeeNavigation, $updatedErpEmployeeNavigation->toArray());
        $dbErpEmployeeNavigation = $this->erpEmployeeNavigationRepo->find($erpEmployeeNavigation->id);
        $this->assertModelData($fakeErpEmployeeNavigation, $dbErpEmployeeNavigation->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_erp_employee_navigation()
    {
        $erpEmployeeNavigation = ErpEmployeeNavigation::factory()->create();

        $resp = $this->erpEmployeeNavigationRepo->delete($erpEmployeeNavigation->id);

        $this->assertTrue($resp);
        $this->assertNull(ErpEmployeeNavigation::find($erpEmployeeNavigation->id), 'ErpEmployeeNavigation should not exist in DB');
    }
}
