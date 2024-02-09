<?php
use App\Http\Controllers\shared\TenantController;
use App\Http\Controllers\API\NavigationAPIController;
use App\Http\Controllers\API\RoleHasPermissionsAPIController;
use App\Http\Controllers\User\API\RegisterController;
use App\Http\Controllers\User\API\ResetPasswordController;
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
    });

    /**
     * tenant related routes
     */
	Route::group(['prefix' => 'tenants'], function (){
       // Route::post('apis', [TenantController::class, 'fetch']);
        Route::get('/list/{userId}', [TenantController::class, 'getTenantList']);
        Route::post('/create', [TenantController::class, 'storeTenant']);
    });

    Route::get('navigation/tree', [NavigationAPIController::class, 'navigationByRole'])->name('navigation by role');
    Route::post('permission/update-role-permission', [RoleHasPermissionsAPIController::class, 'updatePermission'])->name('update role permission');
    Route::get('role/get-form-data', [RoleHasPermissionsAPIController::class, 'getFormData']);
    Route::get('nav/get-all-user-nav', [NavigationAPIController::class, 'getAllUserNav'])->name('get all user navigation');
    Route::post('nav/get-nav-by-type', [NavigationAPIController::class, 'getAllNavByType']);

    Route::resource('navigation_roles', App\Http\Controllers\API\NavigationRoleAPIController::class);

    Route::resource('permissions_models', App\Http\Controllers\API\PermissionsModelAPIController::class);

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

Route::get('tenants/{userId}/{apiKey}', [TenantController::class, 'isTenantExist']);

