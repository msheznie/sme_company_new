<?php namespace Tests\Repositories;

use App\Models\PriceList;
use App\Repositories\PriceListRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PriceListRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PriceListRepository
     */
    protected $priceListRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->priceListRepo = \App::make(PriceListRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_price_list()
    {
        $priceList = PriceList::factory()->make()->toArray();

        $createdPriceList = $this->priceListRepo->create($priceList);

        $createdPriceList = $createdPriceList->toArray();
        $this->assertArrayHasKey('id', $createdPriceList);
        $this->assertNotNull($createdPriceList['id'], 'Created PriceList must have id specified');
        $this->assertNotNull(PriceList::find($createdPriceList['id']), 'PriceList with given id must be in DB');
        $this->assertModelData($priceList, $createdPriceList);
    }

    /**
     * @test read
     */
    public function test_read_price_list()
    {
        $priceList = PriceList::factory()->create();

        $dbPriceList = $this->priceListRepo->find($priceList->id);

        $dbPriceList = $dbPriceList->toArray();
        $this->assertModelData($priceList->toArray(), $dbPriceList);
    }

    /**
     * @test update
     */
    public function test_update_price_list()
    {
        $priceList = PriceList::factory()->create();
        $fakePriceList = PriceList::factory()->make()->toArray();

        $updatedPriceList = $this->priceListRepo->update($fakePriceList, $priceList->id);

        $this->assertModelData($fakePriceList, $updatedPriceList->toArray());
        $dbPriceList = $this->priceListRepo->find($priceList->id);
        $this->assertModelData($fakePriceList, $dbPriceList->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_price_list()
    {
        $priceList = PriceList::factory()->create();

        $resp = $this->priceListRepo->delete($priceList->id);

        $this->assertTrue($resp);
        $this->assertNull(PriceList::find($priceList->id), 'PriceList should not exist in DB');
    }
}
