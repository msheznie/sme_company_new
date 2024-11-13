<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin-settings'], function ()
{
    Route::post('/get-code-configuration', [\App\Http\Controllers\API\CodeConfigurationsAPIController::class,
        'getAllCodeConfiguration'])->name('Get all code configuration');
    Route::post('/code-configuration', [\App\Http\Controllers\API\CodeConfigurationsAPIController::class, 'store'])
        ->name('Create code configuration');
    Route::put('/code-configuration/{id}', [\App\Http\Controllers\API\CodeConfigurationsAPIController::class,
        'update'])->name('Update code configuration');
});
