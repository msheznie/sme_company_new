<?php namespace Tests\Repositories;

use App\Models\CurrencyMaster;
use App\Repositories\CurrencyMasterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CurrencyMasterRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CurrencyMasterRepository
     */
    protected $currencyMasterRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->currencyMasterRepo = \App::make(CurrencyMasterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_currency_master()
    {
        $currencyMaster = CurrencyMaster::factory()->make()->toArray();

        $createdCurrencyMaster = $this->currencyMasterRepo->create($currencyMaster);

        $createdCurrencyMaster = $createdCurrencyMaster->toArray();
        $this->assertArrayHasKey('id', $createdCurrencyMaster);
        $this->assertNotNull($createdCurrencyMaster['id'], 'Created CurrencyMaster must have id specified');
        $this->assertNotNull(CurrencyMaster::find($createdCurrencyMaster['id']), 'CurrencyMaster with given id must be in DB');
        $this->assertModelData($currencyMaster, $createdCurrencyMaster);
    }

    /**
     * @test read
     */
    public function test_read_currency_master()
    {
        $currencyMaster = CurrencyMaster::factory()->create();

        $dbCurrencyMaster = $this->currencyMasterRepo->find($currencyMaster->id);

        $dbCurrencyMaster = $dbCurrencyMaster->toArray();
        $this->assertModelData($currencyMaster->toArray(), $dbCurrencyMaster);
    }

    /**
     * @test update
     */
    public function test_update_currency_master()
    {
        $currencyMaster = CurrencyMaster::factory()->create();
        $fakeCurrencyMaster = CurrencyMaster::factory()->make()->toArray();

        $updatedCurrencyMaster = $this->currencyMasterRepo->update($fakeCurrencyMaster, $currencyMaster->id);

        $this->assertModelData($fakeCurrencyMaster, $updatedCurrencyMaster->toArray());
        $dbCurrencyMaster = $this->currencyMasterRepo->find($currencyMaster->id);
        $this->assertModelData($fakeCurrencyMaster, $dbCurrencyMaster->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_currency_master()
    {
        $currencyMaster = CurrencyMaster::factory()->create();

        $resp = $this->currencyMasterRepo->delete($currencyMaster->id);

        $this->assertTrue($resp);
        $this->assertNull(CurrencyMaster::find($currencyMaster->id), 'CurrencyMaster should not exist in DB');
    }
}
