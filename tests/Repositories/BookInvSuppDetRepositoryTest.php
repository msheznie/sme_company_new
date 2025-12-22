<?php namespace Tests\Repositories;

use App\Models\BookInvSuppDet;
use App\Repositories\BookInvSuppDetRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BookInvSuppDetRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var BookInvSuppDetRepository
     */
    protected $bookInvSuppDetRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->bookInvSuppDetRepo = \App::make(BookInvSuppDetRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_book_inv_supp_det()
    {
        $bookInvSuppDet = BookInvSuppDet::factory()->make()->toArray();

        $createdBookInvSuppDet = $this->bookInvSuppDetRepo->create($bookInvSuppDet);

        $createdBookInvSuppDet = $createdBookInvSuppDet->toArray();
        $this->assertArrayHasKey('id', $createdBookInvSuppDet);
        $this->assertNotNull($createdBookInvSuppDet['id'], 'Created BookInvSuppDet must have id specified');
        $this->assertNotNull(BookInvSuppDet::find($createdBookInvSuppDet['id']), 'BookInvSuppDet with given id must be in DB');
        $this->assertModelData($bookInvSuppDet, $createdBookInvSuppDet);
    }

    /**
     * @test read
     */
    public function test_read_book_inv_supp_det()
    {
        $bookInvSuppDet = BookInvSuppDet::factory()->create();

        $dbBookInvSuppDet = $this->bookInvSuppDetRepo->find($bookInvSuppDet->id);

        $dbBookInvSuppDet = $dbBookInvSuppDet->toArray();
        $this->assertModelData($bookInvSuppDet->toArray(), $dbBookInvSuppDet);
    }

    /**
     * @test update
     */
    public function test_update_book_inv_supp_det()
    {
        $bookInvSuppDet = BookInvSuppDet::factory()->create();
        $fakeBookInvSuppDet = BookInvSuppDet::factory()->make()->toArray();

        $updatedBookInvSuppDet = $this->bookInvSuppDetRepo->update($fakeBookInvSuppDet, $bookInvSuppDet->id);

        $this->assertModelData($fakeBookInvSuppDet, $updatedBookInvSuppDet->toArray());
        $dbBookInvSuppDet = $this->bookInvSuppDetRepo->find($bookInvSuppDet->id);
        $this->assertModelData($fakeBookInvSuppDet, $dbBookInvSuppDet->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_book_inv_supp_det()
    {
        $bookInvSuppDet = BookInvSuppDet::factory()->create();

        $resp = $this->bookInvSuppDetRepo->delete($bookInvSuppDet->id);

        $this->assertTrue($resp);
        $this->assertNull(BookInvSuppDet::find($bookInvSuppDet->id), 'BookInvSuppDet should not exist in DB');
    }
}
