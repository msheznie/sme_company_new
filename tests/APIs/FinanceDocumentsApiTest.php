<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\FinanceDocuments;

class FinanceDocumentsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_finance_documents()
    {
        $financeDocuments = FinanceDocuments::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/finance_documents', $financeDocuments
        );

        $this->assertApiResponse($financeDocuments);
    }

    /**
     * @test
     */
    public function test_read_finance_documents()
    {
        $financeDocuments = FinanceDocuments::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/finance_documents/'.$financeDocuments->id
        );

        $this->assertApiResponse($financeDocuments->toArray());
    }

    /**
     * @test
     */
    public function test_update_finance_documents()
    {
        $financeDocuments = FinanceDocuments::factory()->create();
        $editedFinanceDocuments = FinanceDocuments::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/finance_documents/'.$financeDocuments->id,
            $editedFinanceDocuments
        );

        $this->assertApiResponse($editedFinanceDocuments);
    }

    /**
     * @test
     */
    public function test_delete_finance_documents()
    {
        $financeDocuments = FinanceDocuments::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/finance_documents/'.$financeDocuments->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/finance_documents/'.$financeDocuments->id
        );

        $this->response->assertStatus(404);
    }
}
