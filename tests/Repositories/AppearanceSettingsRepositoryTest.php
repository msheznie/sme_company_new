<?php namespace Tests\Repositories;

use App\Models\AppearanceSettings;
use App\Repositories\AppearanceSettingsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AppearanceSettingsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var AppearanceSettingsRepository
     */
    protected $appearanceSettingsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->appearanceSettingsRepo = \App::make(AppearanceSettingsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_appearance_settings()
    {
        $appearanceSettings = AppearanceSettings::factory()->make()->toArray();

        $createdAppearanceSettings = $this->appearanceSettingsRepo->create($appearanceSettings);

        $createdAppearanceSettings = $createdAppearanceSettings->toArray();
        $this->assertArrayHasKey('id', $createdAppearanceSettings);
        $this->assertNotNull($createdAppearanceSettings['id'], 'Created AppearanceSettings must have id specified');
        $this->assertNotNull(AppearanceSettings::find($createdAppearanceSettings['id']), 'AppearanceSettings with given id must be in DB');
        $this->assertModelData($appearanceSettings, $createdAppearanceSettings);
    }

    /**
     * @test read
     */
    public function test_read_appearance_settings()
    {
        $appearanceSettings = AppearanceSettings::factory()->create();

        $dbAppearanceSettings = $this->appearanceSettingsRepo->find($appearanceSettings->id);

        $dbAppearanceSettings = $dbAppearanceSettings->toArray();
        $this->assertModelData($appearanceSettings->toArray(), $dbAppearanceSettings);
    }

    /**
     * @test update
     */
    public function test_update_appearance_settings()
    {
        $appearanceSettings = AppearanceSettings::factory()->create();
        $fakeAppearanceSettings = AppearanceSettings::factory()->make()->toArray();

        $updatedAppearanceSettings = $this->appearanceSettingsRepo->update($fakeAppearanceSettings, $appearanceSettings->id);

        $this->assertModelData($fakeAppearanceSettings, $updatedAppearanceSettings->toArray());
        $dbAppearanceSettings = $this->appearanceSettingsRepo->find($appearanceSettings->id);
        $this->assertModelData($fakeAppearanceSettings, $dbAppearanceSettings->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_appearance_settings()
    {
        $appearanceSettings = AppearanceSettings::factory()->create();

        $resp = $this->appearanceSettingsRepo->delete($appearanceSettings->id);

        $this->assertTrue($resp);
        $this->assertNull(AppearanceSettings::find($appearanceSettings->id), 'AppearanceSettings should not exist in DB');
    }
}
