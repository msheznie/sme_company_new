<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ContractSettingDetail;

class ContractSettingDetailApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_contract_setting_detail()
    {
        $contractSettingDetail = ContractSettingDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/contract_setting_details', $contractSettingDetail
        );

        $this->assertApiResponse($contractSettingDetail);
    }

    /**
     * @test
     */
    public function test_read_contract_setting_detail()
    {
        $contractSettingDetail = ContractSettingDetail::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/contract_setting_details/'.$contractSettingDetail->id
        );

        $this->assertApiResponse($contractSettingDetail->toArray());
    }

    /**
     * @test
     */
    public function test_update_contract_setting_detail()
    {
        $contractSettingDetail = ContractSettingDetail::factory()->create();
        $editedContractSettingDetail = ContractSettingDetail::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/contract_setting_details/'.$contractSettingDetail->id,
            $editedContractSettingDetail
        );

        $this->assertApiResponse($editedContractSettingDetail);
    }

    /**
     * @test
     */
    public function test_delete_contract_setting_detail()
    {
        $contractSettingDetail = ContractSettingDetail::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/contract_setting_details/'.$contractSettingDetail->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/contract_setting_details/'.$contractSettingDetail->id
        );

        $this->response->assertStatus(404);
    }
}
