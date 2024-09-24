<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ChartOfAccount;

class ChartOfAccountApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_chart_of_account()
    {
        $chartOfAccount = ChartOfAccount::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/chart_of_accounts', $chartOfAccount
        );

        $this->assertApiResponse($chartOfAccount);
    }

    /**
     * @test
     */
    public function test_read_chart_of_account()
    {
        $chartOfAccount = ChartOfAccount::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/chart_of_accounts/'.$chartOfAccount->id
        );

        $this->assertApiResponse($chartOfAccount->toArray());
    }

    /**
     * @test
     */
    public function test_update_chart_of_account()
    {
        $chartOfAccount = ChartOfAccount::factory()->create();
        $editedChartOfAccount = ChartOfAccount::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/chart_of_accounts/'.$chartOfAccount->id,
            $editedChartOfAccount
        );

        $this->assertApiResponse($editedChartOfAccount);
    }

    /**
     * @test
     */
    public function test_delete_chart_of_account()
    {
        $chartOfAccount = ChartOfAccount::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/chart_of_accounts/'.$chartOfAccount->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/chart_of_accounts/'.$chartOfAccount->id
        );

        $this->response->assertStatus(404);
    }
}
