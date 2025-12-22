<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'reports'], function ()
{
    Route::post('/get-contract-details-report',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getContractDetailsReport'])
        ->name('Contract details for report');

    Route::post('/get-contract-report-form-data',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getContractReportFormData'])
        ->name('Contract details form data for report');

    Route::post('/export-contract-details-report',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'exportContractDetailsReport'])
        ->name('Export contract details report');

    Route::post('/contract-milestone-details-report',
        [\App\Http\Controllers\API\MilestonePaymentSchedulesAPIController::class, 'getMilestoneDetailsReport'])
        ->name('Export contract details report');

    Route::post('/export-contract-milestone-report',
        [\App\Http\Controllers\API\MilestonePaymentSchedulesAPIController::class, 'exportContractMilestoneReport'])
        ->name('Export contract milestone report');
});
