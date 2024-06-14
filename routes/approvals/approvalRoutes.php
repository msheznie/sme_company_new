<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'approvals'], function ()
{
    Route::post('contract-approvals', [
        \App\Http\Controllers\API\ContractMasterAPIController::class,
        'getContractApprovals'
    ])->name('Contract approval list');
    Route::post('approve-contract', [
        \App\Http\Controllers\API\ContractMasterAPIController::class,
        'approveContract'
    ])->name('Approve Contract');
    Route::post('reject-contract', [
        \App\Http\Controllers\API\ContractMasterAPIController::class,
        'rejectContract'
    ])->name('Reject Contract');
    Route::post('extend-contract-approvals', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'getContractApprovals'
    ])->name('Extend Contract approval list');
    Route::post('approve-extend-contract', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'approveContract'
    ])->name('Approve Contract');
    Route::post('reject-extend-contract', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'rejectContract'
    ])->name('Reject Contract');
});
