<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'contract'], function (){
    Route::post('/get-contract-master', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getContractMaster'])->name('Contract Master');
    Route::post('/get-all-contract-master-filters', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getAllContractMasterFilters'])->name('Contract Master Filters');
    Route::post('/export-contract-master', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'exportContractMaster'])->name('Export Contract Master');
    Route::post('/delete-file-from-aws', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'deleteFileFromAws'])->name('Delete File From S3');
    Route::post('/get-counter-party-names', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getCounterPartyNames'])->name('Counter Party Names');
});
