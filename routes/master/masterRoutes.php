<?php

Route::group(['prefix' => 'contract-users'], function (){
    Route::post('/', [\App\Http\Controllers\API\ContractUsersAPIController::class, 'store'])->name('Store contract user');
    Route::get('/{id}', [\App\Http\Controllers\API\ContractUsersAPIController::class, 'show'])->name('Show contract user');
    Route::put('/{id}', [\App\Http\Controllers\API\ContractUsersAPIController::class, 'update'])->name('Update contract user');
    Route::delete('/{id}', [\App\Http\Controllers\API\ContractUsersAPIController::class, 'destroy'])->name('Delete contract user');
    Route::post('/list', [\App\Http\Controllers\API\ContractUsersAPIController::class, 'contractUserList'])->name('Contract Users List');
    Route::post('/form-data', [\App\Http\Controllers\API\ContractUsersAPIController::class, 'contractUserFormData'])->name('Contract Users Filters');

    Route::resource('customer_masters', App\Http\Controllers\API\CustomerMasterAPIController::class);
});
