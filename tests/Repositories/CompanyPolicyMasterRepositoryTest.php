<?php namespace Tests\Repositories;

use App\Models\CompanyPolicyMaster;
use App\Repositories\CompanyPolicyMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CompanyPolicyMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CompanyPolicyMasterRepository
     */
    protected $companyPolicyMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->companyPolicyMasterRepo = \App::make(CompanyPolicyMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_company_policy_master()
    {
        $companyPolicyMaster = CompanyPolicyMaster::factory()->make()->toArray();

        $createdCompanyPolicyMaster = $this->companyPolicyMasterRepo->create($companyPolicyMaster);

        $createdCompanyPolicyMaster = $createdCompanyPolicyMaster->toArray();
        $this->assertArrayHasKey('id', $createdCompanyPolicyMaster);
        $this->assertNotNull($createdCompanyPolicyMaster['id'], 'Created CompanyPolicyMaster must have id specified');
        $this->assertNotNull(CompanyPolicyMaster::find($createdCompanyPolicyMaster['id']), 'CompanyPolicyMaster with given id must be in DB');
        $this->assertModelData($companyPolicyMaster, $createdCompanyPolicyMaster);
    }

    /**
     * @test read
     */
    public function test_read_company_policy_master()
    {
        $companyPolicyMaster = CompanyPolicyMaster::factory()->create();

        $dbCompanyPolicyMaster = $this->companyPolicyMasterRepo->find($companyPolicyMaster->id);

        $dbCompanyPolicyMaster = $dbCompanyPolicyMaster->toArray();
        $this->assertModelData($companyPolicyMaster->toArray(), $dbCompanyPolicyMaster);
    }

    /**
     * @test update
     */
    public function test_update_company_policy_master()
    {
        $companyPolicyMaster = CompanyPolicyMaster::factory()->create();
        $fakeCompanyPolicyMaster = CompanyPolicyMaster::factory()->make()->toArray();

        $updatedCompanyPolicyMaster = $this->companyPolicyMasterRepo->update($fakeCompanyPolicyMaster, $companyPolicyMaster->id);

        $this->assertModelData($fakeCompanyPolicyMaster, $updatedCompanyPolicyMaster->toArray());
        $dbCompanyPolicyMaster = $this->companyPolicyMasterRepo->find($companyPolicyMaster->id);
        $this->assertModelData($fakeCompanyPolicyMaster, $dbCompanyPolicyMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_company_policy_master()
    {
        $companyPolicyMaster = CompanyPolicyMaster::factory()->create();

        $resp = $this->companyPolicyMasterRepo->delete($companyPolicyMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(CompanyPolicyMaster::find($companyPolicyMaster->id), 'CompanyPolicyMaster should not exist in DB');
    }
}
