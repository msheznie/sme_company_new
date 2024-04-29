<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'contract'], function (){
    Route::post('/get-contract-master', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getContractMaster'])->name('Contract Master');
    Route::post('/get-all-contract-master-filters', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getAllContractMasterFilters'])->name('Contract Master Filters');
    Route::post('/export-contract-master', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'exportContractMaster'])->name('Export Contract Master');
    Route::post('/delete-file-from-aws', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'deleteFileFromAws'])->name('Delete File From S3');
    Route::post('/get-counter-party-names', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getCounterPartyNames'])->name('Counter Party Names');
    Route::get('/contract-master/{id}', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'show'])->name('Show contract');
    Route::put('/contract-master/{id}', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'update'])->name('Update contract');
    Route::post('/contract-master', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'store'])->name('Create Contract Master');
    Route::post('/user-form-data', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getUserFormDataContractEdit'])->name('Contract edit form data');
    Route::post('/get-contract-type-sections-data', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getContractTypeSectionData'])->name('Contract type section data');
    Route::post('/get-active-contract-section-details', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getActiveContractSectionDetails'])->name('Active Contract Section  Details');
    Route::post('/update-contract-setting-details', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'updateContractSettingDetails'])->name('Update contract setting detail');
    Route::get('/get-contract-milestones/{id}', [\App\Http\Controllers\API\ContractMilestoneAPIController::class, 'getContractMilestones'])->name('get contract milestones');
    Route::post('/milestone', [\App\Http\Controllers\API\ContractMilestoneAPIController::class, 'store'])->name('store contract milestones');
    Route::put('/milestone/{id}', [\App\Http\Controllers\API\ContractMilestoneAPIController::class, 'update'])->name('update contract milestones');
    Route::post('/get-contract-deliverables', [\App\Http\Controllers\API\ContractDeliverablesAPIController::class, 'getContractDeliverables'])->name('get contract deliverables');
    Route::post('/get-contract-overall-retention-data', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getContractOverallRetentionData'])->name('Contract Overall Retention Data');
    Route::post('/update-overall-retention', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'updateOverallRetention'])->name('Update Overall Retention');
});
