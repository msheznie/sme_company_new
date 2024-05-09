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

Route::group(['prefix' => 'contract-user-group'], function (){
    Route::post('/list', [\App\Http\Controllers\API\ContractUserGroupAPIController::class, 'contractUserListForUserGroup'])->name('contract users List');
    Route::post('/', [\App\Http\Controllers\API\ContractUserGroupAPIController::class, 'store'])->name('store contract user group');
    Route::post('/list-user-groups', [\App\Http\Controllers\API\ContractUserGroupAPIController::class, 'getContractUserGroupList'])->name('contract user group list');
    Route::post('/assigned-users', [\App\Http\Controllers\API\ContractUserGroupAPIController::class, 'getContractUserGroupAssignedUsers'])->name('contract user group list');
    Route::put('/{id}', [\App\Http\Controllers\API\ContractUserGroupAPIController::class, 'updateStatus'])->name('update contract user group');
    Route::delete('/{id}', [\App\Http\Controllers\API\ContractUserGroupAPIController::class, 'removeAssignedUserFromUserGroup'])->name('remove assigned user from user group');
    Route::post('/list-user-group', [\App\Http\Controllers\API\ContractUserGroupAPIController::class, 'contractUserList'])->name('Contract Users List');
});
