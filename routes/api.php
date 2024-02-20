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

Route::group(['middleware' => ['tenant']], function () {
    Route::post('login', 'AuthAPIController@auth');
    Route::post('oauth/login_with_token', 'AuthAPIController@authWithToken');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('current/user', 'UsersAPIController@getCurrentUser');
        Route::get('get-company-list', 'ErpEmployeeNavigationAPIController@getCompanyList');

        /* Routes not in use */
        Route::resource('users', App\Http\Controllers\API\UsersAPIController::class);
        Route::resource('employees', App\Http\Controllers\API\EmployeesAPIController::class);
        Route::resource('web_employee_profiles', App\Http\Controllers\API\WebEmployeeProfileAPIController::class);
        Route::resource('companies', App\Http\Controllers\API\CompanyAPIController::class);
        Route::resource('erp_employee_navigations', App\Http\Controllers\API\ErpEmployeeNavigationAPIController::class);
        Route::resource('navigation_user_group_setups', App\Http\Controllers\API\NavigationUserGroupSetupAPIController::class);
    });
});
