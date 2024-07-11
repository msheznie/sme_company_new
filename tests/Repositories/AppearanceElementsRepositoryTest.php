<?php namespace Tests\Repositories;

use App\Models\AppearanceElements;
use App\Repositories\AppearanceElementsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AppearanceElementsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var AppearanceElementsRepository
     */
    protected $appearanceElementsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->appearanceElementsRepo = \App::make(AppearanceElementsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_appearance_elements()
    {
        $appearanceElements = AppearanceElements::factory()->make()->toArray();

        $createdAppearanceElements = $this->appearanceElementsRepo->create($appearanceElements);

        $createdAppearanceElements = $createdAppearanceElements->toArray();
        $this->assertArrayHasKey('id', $createdAppearanceElements);
        $this->assertNotNull($createdAppearanceElements['id'], 'Created AppearanceElements must have id specified');
        $this->assertNotNull(AppearanceElements::find($createdAppearanceElements['id']), 'AppearanceElements with given id must be in DB');
        $this->assertModelData($appearanceElements, $createdAppearanceElements);
    }

    /**
     * @test read
     */
    public function test_read_appearance_elements()
    {
        $appearanceElements = AppearanceElements::factory()->create();

        $dbAppearanceElements = $this->appearanceElementsRepo->find($appearanceElements->id);

        $dbAppearanceElements = $dbAppearanceElements->toArray();
        $this->assertModelData($appearanceElements->toArray(), $dbAppearanceElements);
    }

    /**
     * @test update
     */
    public function test_update_appearance_elements()
    {
        $appearanceElements = AppearanceElements::factory()->create();
        $fakeAppearanceElements = AppearanceElements::factory()->make()->toArray();

        $updatedAppearanceElements = $this->appearanceElementsRepo->update($fakeAppearanceElements, $appearanceElements->id);

        $this->assertModelData($fakeAppearanceElements, $updatedAppearanceElements->toArray());
        $dbAppearanceElements = $this->appearanceElementsRepo->find($appearanceElements->id);
        $this->assertModelData($fakeAppearanceElements, $dbAppearanceElements->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_appearance_elements()
    {
        $appearanceElements = AppearanceElements::factory()->create();

        $resp = $this->appearanceElementsRepo->delete($appearanceElements->id);

        $this->assertTrue($resp);
        $this->assertNull(AppearanceElements::find($appearanceElements->id), 'AppearanceElements should not exist in DB');
    }
}
