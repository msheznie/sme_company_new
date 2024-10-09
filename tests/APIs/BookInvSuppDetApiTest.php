<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\BookInvSuppDet;

class BookInvSuppDetApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_book_inv_supp_det()
    {
        $bookInvSuppDet = BookInvSuppDet::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/book_inv_supp_dets', $bookInvSuppDet
        );

        $this->assertApiResponse($bookInvSuppDet);
    }

    /**
     * @test
     */
    public function test_read_book_inv_supp_det()
    {
        $bookInvSuppDet = BookInvSuppDet::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/book_inv_supp_dets/'.$bookInvSuppDet->id
        );

        $this->assertApiResponse($bookInvSuppDet->toArray());
    }

    /**
     * @test
     */
    public function test_update_book_inv_supp_det()
    {
        $bookInvSuppDet = BookInvSuppDet::factory()->create();
        $editedBookInvSuppDet = BookInvSuppDet::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/book_inv_supp_dets/'.$bookInvSuppDet->id,
            $editedBookInvSuppDet
        );

        $this->assertApiResponse($editedBookInvSuppDet);
    }

    /**
     * @test
     */
    public function test_delete_book_inv_supp_det()
    {
        $bookInvSuppDet = BookInvSuppDet::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/book_inv_supp_dets/'.$bookInvSuppDet->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/book_inv_supp_dets/'.$bookInvSuppDet->id
        );

        $this->response->assertStatus(404);
    }
}
