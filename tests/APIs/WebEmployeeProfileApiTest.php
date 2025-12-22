<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\WebEmployeeProfile;

class WebEmployeeProfileApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_web_employee_profile()
    {
        $webEmployeeProfile = WebEmployeeProfile::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/web_employee_profiles', $webEmployeeProfile
        );

        $this->assertApiResponse($webEmployeeProfile);
    }

    /**
     * @test
     */
    public function test_read_web_employee_profile()
    {
        $webEmployeeProfile = WebEmployeeProfile::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/web_employee_profiles/'.$webEmployeeProfile->id
        );

        $this->assertApiResponse($webEmployeeProfile->toArray());
    }

    /**
     * @test
     */
    public function test_update_web_employee_profile()
    {
        $webEmployeeProfile = WebEmployeeProfile::factory()->create();
        $editedWebEmployeeProfile = WebEmployeeProfile::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/web_employee_profiles/'.$webEmployeeProfile->id,
            $editedWebEmployeeProfile
        );

        $this->assertApiResponse($editedWebEmployeeProfile);
    }

    /**
     * @test
     */
    public function test_delete_web_employee_profile()
    {
        $webEmployeeProfile = WebEmployeeProfile::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/web_employee_profiles/'.$webEmployeeProfile->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/web_employee_profiles/'.$webEmployeeProfile->id
        );

        $this->response->assertStatus(404);
    }
}
