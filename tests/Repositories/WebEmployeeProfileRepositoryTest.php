<?php namespace Tests\Repositories;

use App\Models\WebEmployeeProfile;
use App\Repositories\WebEmployeeProfileRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class WebEmployeeProfileRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var WebEmployeeProfileRepository
     */
    protected $webEmployeeProfileRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->webEmployeeProfileRepo = \App::make(WebEmployeeProfileRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_web_employee_profile()
    {
        $webEmployeeProfile = WebEmployeeProfile::factory()->make()->toArray();

        $createdWebEmployeeProfile = $this->webEmployeeProfileRepo->create($webEmployeeProfile);

        $createdWebEmployeeProfile = $createdWebEmployeeProfile->toArray();
        $this->assertArrayHasKey('id', $createdWebEmployeeProfile);
        $this->assertNotNull($createdWebEmployeeProfile['id'], 'Created WebEmployeeProfile must have id specified');
        $this->assertNotNull(WebEmployeeProfile::find($createdWebEmployeeProfile['id']), 'WebEmployeeProfile with given id must be in DB');
        $this->assertModelData($webEmployeeProfile, $createdWebEmployeeProfile);
    }

    /**
     * @test read
     */
    public function test_read_web_employee_profile()
    {
        $webEmployeeProfile = WebEmployeeProfile::factory()->create();

        $dbWebEmployeeProfile = $this->webEmployeeProfileRepo->find($webEmployeeProfile->id);

        $dbWebEmployeeProfile = $dbWebEmployeeProfile->toArray();
        $this->assertModelData($webEmployeeProfile->toArray(), $dbWebEmployeeProfile);
    }

    /**
     * @test update
     */
    public function test_update_web_employee_profile()
    {
        $webEmployeeProfile = WebEmployeeProfile::factory()->create();
        $fakeWebEmployeeProfile = WebEmployeeProfile::factory()->make()->toArray();

        $updatedWebEmployeeProfile = $this->webEmployeeProfileRepo->update($fakeWebEmployeeProfile, $webEmployeeProfile->id);

        $this->assertModelData($fakeWebEmployeeProfile, $updatedWebEmployeeProfile->toArray());
        $dbWebEmployeeProfile = $this->webEmployeeProfileRepo->find($webEmployeeProfile->id);
        $this->assertModelData($fakeWebEmployeeProfile, $dbWebEmployeeProfile->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_web_employee_profile()
    {
        $webEmployeeProfile = WebEmployeeProfile::factory()->create();

        $resp = $this->webEmployeeProfileRepo->delete($webEmployeeProfile->id);

        $this->assertTrue($resp);
        $this->assertNull(WebEmployeeProfile::find($webEmployeeProfile->id), 'WebEmployeeProfile should not exist in DB');
    }
}
