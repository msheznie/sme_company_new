<?php

namespace App\Providers;

use App\Interfaces\AdditionalDocumentRepositoryInterface;
use App\Interfaces\ContractDocumentRepositoryInterface;
use App\Repositories\ContractAdditionalDocumentsRepository;
use App\Repositories\ContractDocumentRepository;
use App\Repositories\ErpDocumentAttachmentsRepository;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ErpDocumentAttachmentsRepository::class,
            function ($app)
            {
                return new ErpDocumentAttachmentsRepository($app);
            }
        );

        $this->app->bind(
            ContractDocumentRepositoryInterface::class,
            function ($app)
            {
                $erpDocumentAttachmentsRepository = $app->make(ErpDocumentAttachmentsRepository::class);

                return new ContractDocumentRepository(
                    $app,
                    $erpDocumentAttachmentsRepository
                );
            }
        );

        $this->app->bind(
            AdditionalDocumentRepositoryInterface::class,
            function ($app)
            {
                $erpDocumentAttachmentsRepository = $app->make(ErpDocumentAttachmentsRepository::class);
                return new ContractAdditionalDocumentsRepository(
                    $app,
                    $erpDocumentAttachmentsRepository
                );
            }
        );

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
