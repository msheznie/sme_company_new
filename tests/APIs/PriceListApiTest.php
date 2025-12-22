<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PriceList;

class PriceListApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_price_list()
    {
        $priceList = PriceList::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/price_lists', $priceList
        );

        $this->assertApiResponse($priceList);
    }

    /**
     * @test
     */
    public function test_read_price_list()
    {
        $priceList = PriceList::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/price_lists/'.$priceList->id
        );

        $this->assertApiResponse($priceList->toArray());
    }

    /**
     * @test
     */
    public function test_update_price_list()
    {
        $priceList = PriceList::factory()->create();
        $editedPriceList = PriceList::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/price_lists/'.$priceList->id,
            $editedPriceList
        );

        $this->assertApiResponse($editedPriceList);
    }

    /**
     * @test
     */
    public function test_delete_price_list()
    {
        $priceList = PriceList::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/price_lists/'.$priceList->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/price_lists/'.$priceList->id
        );

        $this->response->assertStatus(404);
    }
}
