<?php

namespace App\Providers;

use App\Interfaces\UserTenantRepositoryInterface;
use App\Repositories\CMContractMasterAmdRepository;
use App\Repositories\CMContractUserAssignAmdRepository;
use App\Repositories\ContractMasterRepository;
use App\Repositories\ContractUserAssignRepository;
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

        $this->app->singleton(CMContractMasterAmdRepository::class, function ($app)
        {
            return new CMContractMasterAmdRepository($app->make(ContractMasterRepository::class),$app);
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
