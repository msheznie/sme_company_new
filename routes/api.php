<?php

use App\Http\Controllers\DeliveryAppointment\API\AppointmentController;
use App\Http\Controllers\ERP\APIController;
use App\Http\Controllers\shared\TenantController;
use App\Http\Controllers\API\NavigationAPIController;
use App\Http\Controllers\API\PriceListAPIController;
use App\Http\Controllers\API\RoleHasPermissionsAPIController;
use App\Http\Controllers\API\SupplierMasterHistoryAPIController;
use App\Http\Controllers\Supplier\API\SupplierRegistrationController;
use App\Http\Controllers\Tender\API\TenderManagementController;
use App\Http\Controllers\User\API\SupplierDetailController;
use App\Http\Controllers\User\API\RegisterController;
use App\Http\Controllers\User\API\ResetPasswordController;
use App\Http\Controllers\User\API\FormOptionDetailsController;
use App\Http\Controllers\User\API\UserController;
use App\Http\Controllers\User\API\VerificationController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:api','navigation_auth']], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
        Route::post('/send-activation-email', [UserController::class, 'sendActivationEmail']);
    });

    /**
     * tenant related routes
     */
	Route::group(['prefix' => 'tenants'], function (){
       // Route::post('apis', [TenantController::class, 'fetch']);
        Route::get('/list/{userId}', [TenantController::class, 'getTenantList']);
        Route::post('/create', [TenantController::class, 'storeTenant']);
        Route::get('/kyc-status/{userId}/{tenantId}', [TenantController::class, 'getKycStatus']);
    });

    /**
     * supplier related routes
     */
    Route::group(['prefix' => 'suppliers'], function (){
        Route::group(['prefix' => 'registration'], function(){
            Route::get('/', [SupplierRegistrationController::class, 'index'])->name('kyc index');
            Route::post('/', [SupplierRegistrationController::class, 'store']);
            Route::post('/details', [SupplierRegistrationController::class, 'show'])->name('Supplier Details');;
            Route::post('/files/upload', [SupplierRegistrationController::class, 'uploadHandler']);
            Route::get('/downloads/attachment', [SupplierRegistrationController::class, 'downloadAttachment']);
            Route::post('/status', [SupplierRegistrationController::class, 'updateSupplierKYCFormStatus']);
            Route::post('/insert_tenant_data', [SupplierRegistrationController::class, 'insertTenantData']);
            Route::get('/downloads/excel-template-download', [SupplierRegistrationController::class, 'downloadExcelTemplateDownload']);
            Route::post('/ammendKYCDetails', [SupplierMasterHistoryAPIController::class, 'ammendKYCApprovalDetails']);
        });
    });

    Route::get('navigation/tree', [NavigationAPIController::class, 'navigationByRole'])->name('navigation by role');
    Route::post('permission/update-role-permission', [RoleHasPermissionsAPIController::class, 'updatePermission'])->name('update role permission');
    Route::get('role/get-form-data', [RoleHasPermissionsAPIController::class, 'getFormData']);
    Route::get('nav/get-all-user-nav', [NavigationAPIController::class, 'getAllUserNav'])->name('get all user navigation');
    Route::post('nav/get-nav-by-type', [NavigationAPIController::class, 'getAllNavByType']);

    //KYC-Form
    Route::get('/get-form-option', [FormOptionDetailsController::class, 'getSelectOptionValues']);
    Route::post('/save/{formId}', [SupplierRegistrationController::class, 'create']);
    Route::get('/get-api-key', [SupplierRegistrationController::class, 'getApiKey']);

    Route::post('/price-list/excel-bulk-upload', [PriceListAPIController::class, 'excelBulkUpload']);
    Route::post('/price-list/store-price-list', [PriceListAPIController::class, 'storePriceList'])->name('price list store');
    Route::get('/price-list/get-form-data', [PriceListAPIController::class, 'getFormData']);
    Route::post('/price-list/get-price-list', [PriceListAPIController::class, 'getPriceList'])->name('price list index');
    Route::post('/price-list/delete-price-list', [PriceListAPIController::class, 'destroy'])->name('price list destroy');
    Route::post('/price-list/edit-data-price-list', [PriceListAPIController::class, 'editDataPriceList']);
    Route::post('/price-list/update-price-list', [PriceListAPIController::class, 'updatePriceList'])->name('price list update');

    Route::group(['prefix' => 'suppliers'], function (){
        Route::group(['prefix' => 'appointment'], function(){
            Route::get('/downloads/attachment', [AppointmentController::class, 'downloadAttachment']);
            Route::get('/remove/attachment', [AppointmentController::class, 'removeAttachment']);
        });
    });

    Route::group(['prefix' => 'tender'], function (){
        Route::group(['prefix' => 'prebid'], function(){
            Route::get('/downloads/attachment', [TenderManagementController::class, 'downloadAttachment']);
            Route::get('/remove/attachment', [TenderManagementController::class, 'removeAttachment']);
        });
    });

    Route::resource('navigation_roles', App\Http\Controllers\API\NavigationRoleAPIController::class);

    Route::resource('permissions_models', App\Http\Controllers\API\PermissionsModelAPIController::class);

    Route::resource('price_lists', App\Http\Controllers\API\PriceListAPIController::class);

    Route::resource('currency_masters', App\Http\Controllers\API\CurrencyMasterAPIController::class);

});

Route::group(['middleware' => ['navigation_auth']], function () {
    Route::group(['prefix' => 'tenants'], function (){
        Route::post('apis', [TenantController::class, 'fetch']);
    });
});


Route::post('users', [RegisterController::class, 'store'])->name('user.store');
Route::get('users/{email}/password-reset', [VerificationController::class, 'resend'])->name('verification resend');
Route::post('/reset-password', [ResetPasswordController::class, 'update'])->name('password confirm');
Route::post('/reset-password-attempt', [ResetPasswordController::class, 'isValidAttempt'])->name('reset password attempt');
Route::post('/forgot-password', [ResetPasswordController::class, 'store'])->name('password reset');

Route::get('artisan-command/{command}', function ($command) {
    \Illuminate\Support\Facades\Artisan::call($command);
    return $command .' successfully run';
});

Route::get('verify/email/{email}/{registration_number}', [RegisterController::class, 'isEmailBusinessRegistrationNumberExist']);
Route::get('tenants/{userId}/{apiKey}', [TenantController::class, 'isTenantExist']);

Route::group(['prefix' => 'erp'], function (){
    Route::post('requests', [APIController::class, 'handleRequest']);
});








Route::resource('supplier_master_histories', App\Http\Controllers\API\SupplierMasterHistoryAPIController::class);


Route::resource('supplier_detail_histories', App\Http\Controllers\API\SupplierDetailHistoryAPIController::class);
