<?php

namespace App\Providers;

use App\Interfaces\SupplierDetailsRepositoryInterface;
use App\Interfaces\UserTenantRepositoryInterface;
use App\Repositories\FormOptionDetailsRepository;
use App\Interfaces\FormOptionDetailsRepositoryInterface;
use App\Repositories\SupplierDetailsRepository;
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
        $this->app->bind(FormOptionDetailsRepositoryInterface::class, FormOptionDetailsRepository::class);
        $this->app->bind(SupplierDetailsRepositoryInterface::class, SupplierDetailsRepository::class);
        $this->app->bind(UserTenantRepositoryInterface::class, UserTenantRepository::class);
    }
}
