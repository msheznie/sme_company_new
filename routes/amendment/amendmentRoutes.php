<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'amendment'], function ()
{
    Route::get('/get-contract-payment-terms-amd/{id}',
        [\App\Http\Controllers\API\ContractPaymentTermsAmdAPIController::class, 'getContractPaymentTermsAmd'])
        ->name('get contract payment terms amd');
});
