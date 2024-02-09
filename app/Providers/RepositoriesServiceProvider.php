<?php

namespace App\Providers;

use App\Interfaces\UserTenantRepositoryInterface;
use App\Repositories\UserTenantRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserTenantRepositoryInterface::class, UserTenantRepository::class);
    }
}
