<?php

use App\Http\Controllers\API\CMContractTypesAPIController;
use App\Http\Controllers\API\ConfigurationAPIController;
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

Route::group(['middleware' => ['tenant']], function ()
{
    Route::post('login', 'AuthAPIController@auth');
    Route::post('oauth/login_with_token', 'AuthAPIController@authWithToken');

    Route::resource('c_m_parties_masters', App\Http\Controllers\API\CMPartiesMasterAPIController::class);
    Route::resource('c_m_intents_masters', App\Http\Controllers\API\CMIntentsMasterAPIController::class);
    Route::resource('c_m_counter_parties_masters', App\Http\Controllers\API\CMCounterPartiesMasterAPIController::class);
    Route::resource('c_m_contracts_masters', App\Http\Controllers\API\CMContractsMasterAPIController::class);
    Route::resource('c_m_contract_types', App\Http\Controllers\API\CMContractTypesAPIController::class);
    Route::resource('c_m_contract_type_sections', App\Http\Controllers\API\CMContractTypeSectionsAPIController::class);


    Route::group(['middleware' => ['third_party_integration']], function ()
    {
        Route::get('get_contract_data', 'ContractMasterAPIController@getContractData');
    });

    Route::group(['middleware' => ['auth:api']], function ()
    {
        Route::get('current/user', 'UsersAPIController@getCurrentUser');
        Route::get('get-company-list', 'ErpEmployeeNavigationAPIController@getCompanyList');
        Route::get('get-configuration-info', 'ConfigurationAPIController@getConfigurationInfo');

        Route::group(['middleware' => ['company']], function ()
        {
            require_once __DIR__.'/../routes/approvals/approvalRoutes.php';
            require_once __DIR__.'/../routes/contracts/contractsRoutes.php';
            require_once __DIR__.'/../routes/master/masterRoutes.php';
            require_once __DIR__.'/../routes/common/commonRoutes.php';
            require_once __DIR__.'/../routes/reports/reportRoutes.php';


            Route::post('/save-contract-type', [CMContractTypesAPIController::class, 'saveContractType'])
                ->name('Contract Type store');
            Route::post('/get-contract-type', [CMContractTypesAPIController::class, 'getContractType'])
                ->name('Contract Type index');
            Route::post('/delete-contract-type', [CMContractTypesAPIController::class, 'deleteContractType'])
                ->name('Contract Type destroy');
            Route::post('/get-all-contract-filters', [CMContractTypesAPIController::class, 'getAllContractFilters'])
                ->name('Contract Filters');
            Route::post('/export-contract-types', [CMContractTypesAPIController::class, 'exportContractTypes'])
                ->name('Export Contract Types');
            Route::post('/delete-file-from-aws', [CMContractTypesAPIController::class, 'deleteFileFromAws'])
                ->name('Delete File From S3');
            Route::post('/get-sections-filter-drop', [CMContractTypesAPIController::class, 'getSectionsFilterDrop']);
            Route::post('/update-dynamic-field-detail', [
                CMContractTypesAPIController::class,
                'updateDynamicFieldDetail'
            ]);
        });

        /* Routes not in use */
        Route::resource('users', App\Http\Controllers\API\UsersAPIController::class);
        Route::resource('employees', App\Http\Controllers\API\EmployeesAPIController::class);
        Route::resource('web_employee_profiles', App\Http\Controllers\API\WebEmployeeProfileAPIController::class);
        Route::resource('companies', App\Http\Controllers\API\CompanyAPIController::class);
        Route::resource('erp_employee_navigations', App\Http\Controllers\API\ErpEmployeeNavigationAPIController::class);
        Route::resource('navigation_user_group_setups',
            App\Http\Controllers\API\NavigationUserGroupSetupAPIController::class);
        Route::resource('c_m_contract_sections_masters',
            App\Http\Controllers\API\CMContractSectionsMasterAPIController::class);
        Route::resource('employees_details', App\Http\Controllers\API\EmployeesDetailsAPIController::class);
    });
});

Route::get('/activate-contract', function ()
{
    \Artisan::call('activateContract');
    return 'Contracts Activate Successfully!';
});

Route::get('/contract-expiry-reminder', function ()
{
    \Artisan::call('reminderContractExpiry');
    return 'Contracts Reminder Expiry Send Successfully!';
});
