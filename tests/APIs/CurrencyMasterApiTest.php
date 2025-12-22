<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CurrencyMaster;

class CurrencyMasterApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_currency_master()
    {
        $currencyMaster = CurrencyMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/currency_masters', $currencyMaster
        );

        $this->assertApiResponse($currencyMaster);
    }

    /**
     * @test
     */
    public function test_read_currency_master()
    {
        $currencyMaster = CurrencyMaster::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/currency_masters/'.$currencyMaster->id
        );

        $this->assertApiResponse($currencyMaster->toArray());
    }

    /**
     * @test
     */
    public function test_update_currency_master()
    {
        $currencyMaster = CurrencyMaster::factory()->create();
        $editedCurrencyMaster = CurrencyMaster::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/currency_masters/'.$currencyMaster->id,
            $editedCurrencyMaster
        );

        $this->assertApiResponse($editedCurrencyMaster);
    }

    /**
     * @test
     */
    public function test_delete_currency_master()
    {
        $currencyMaster = CurrencyMaster::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/currency_masters/'.$currencyMaster->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/currency_masters/'.$currencyMaster->id
        );

        $this->response->assertStatus(404);
    }
}
