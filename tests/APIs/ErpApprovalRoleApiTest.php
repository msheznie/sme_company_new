<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ErpApprovalRole;

class ErpApprovalRoleApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_erp_approval_role()
    {
        $erpApprovalRole = ErpApprovalRole::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/erp_approval_roles', $erpApprovalRole
        );

        $this->assertApiResponse($erpApprovalRole);
    }

    /**
     * @test
     */
    public function test_read_erp_approval_role()
    {
        $erpApprovalRole = ErpApprovalRole::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/erp_approval_roles/'.$erpApprovalRole->id
        );

        $this->assertApiResponse($erpApprovalRole->toArray());
    }

    /**
     * @test
     */
    public function test_update_erp_approval_role()
    {
        $erpApprovalRole = ErpApprovalRole::factory()->create();
        $editedErpApprovalRole = ErpApprovalRole::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/erp_approval_roles/'.$erpApprovalRole->id,
            $editedErpApprovalRole
        );

        $this->assertApiResponse($editedErpApprovalRole);
    }

    /**
     * @test
     */
    public function test_delete_erp_approval_role()
    {
        $erpApprovalRole = ErpApprovalRole::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/erp_approval_roles/'.$erpApprovalRole->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/erp_approval_roles/'.$erpApprovalRole->id
        );

        $this->response->assertStatus(404);
    }
}
