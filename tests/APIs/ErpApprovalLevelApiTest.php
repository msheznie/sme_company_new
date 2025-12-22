<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ErpApprovalLevel;

class ErpApprovalLevelApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_erp_approval_level()
    {
        $erpApprovalLevel = ErpApprovalLevel::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/erp_approval_levels', $erpApprovalLevel
        );

        $this->assertApiResponse($erpApprovalLevel);
    }

    /**
     * @test
     */
    public function test_read_erp_approval_level()
    {
        $erpApprovalLevel = ErpApprovalLevel::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/erp_approval_levels/'.$erpApprovalLevel->id
        );

        $this->assertApiResponse($erpApprovalLevel->toArray());
    }

    /**
     * @test
     */
    public function test_update_erp_approval_level()
    {
        $erpApprovalLevel = ErpApprovalLevel::factory()->create();
        $editedErpApprovalLevel = ErpApprovalLevel::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/erp_approval_levels/'.$erpApprovalLevel->id,
            $editedErpApprovalLevel
        );

        $this->assertApiResponse($editedErpApprovalLevel);
    }

    /**
     * @test
     */
    public function test_delete_erp_approval_level()
    {
        $erpApprovalLevel = ErpApprovalLevel::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/erp_approval_levels/'.$erpApprovalLevel->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/erp_approval_levels/'.$erpApprovalLevel->id
        );

        $this->response->assertStatus(404);
    }
}
