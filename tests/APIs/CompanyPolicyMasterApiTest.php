<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CompanyPolicyMaster;

class CompanyPolicyMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_company_policy_master()
    {
        $companyPolicyMaster = CompanyPolicyMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/company_policy_masters', $companyPolicyMaster
        );

        $this->assertApiResponse($companyPolicyMaster);
    }

    /**
     * @test
     */
    public function test_read_company_policy_master()
    {
        $companyPolicyMaster = CompanyPolicyMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/company_policy_masters/'.$companyPolicyMaster->id
        );

        $this->assertApiResponse($companyPolicyMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_company_policy_master()
    {
        $companyPolicyMaster = CompanyPolicyMaster::factory()->create();
        $editedCompanyPolicyMaster = CompanyPolicyMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/company_policy_masters/'.$companyPolicyMaster->id,
            $editedCompanyPolicyMaster
        );

        $this->assertApiResponse($editedCompanyPolicyMaster);
    }

    /**
     * @test
     */
    public function test_delete_company_policy_master()
    {
        $companyPolicyMaster = CompanyPolicyMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/company_policy_masters/'.$companyPolicyMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/company_policy_masters/'.$companyPolicyMaster->id
        );

        $this->response->assertStatus(404);
    }
}
