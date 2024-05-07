<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'contract'], function (){
    Route::post('/get-contract-master',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getContractMaster'])
        ->name('Contract Master');
    Route::post('/get-all-contract-master-filters',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getAllContractMasterFilters'])
        ->name('Contract Master Filters');
    Route::post('/export-contract-master',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'exportContractMaster'])
        ->name('Export Contract Master');
    Route::post('/delete-file-from-aws',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'deleteFileFromAws'])
        ->name('Delete File From S3');
    Route::post('/get-counter-party-names',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getCounterPartyNames'])
        ->name('Counter Party Names');
    Route::get('/contract-master/{id}', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'show'])
        ->name('Show contract');
    Route::put('/contract-master/{id}', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'update'])
        ->name('Update contract');
    Route::post('/contract-master', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'store'])
        ->name('Create Contract Master');
    Route::post('/user-form-data',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getUserFormDataContractEdit'])
        ->name('Contract edit form data');
    Route::post('/get-contract-type-sections-data',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getContractTypeSectionData'])
        ->name('Contract type section data');
    Route::post('/get-active-contract-section-details',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getActiveContractSectionDetails'])
        ->name('Active Contract Section  Details');
    Route::post('/update-contract-setting-details',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'updateContractSettingDetails'])
        ->name('Update contract setting detail');
    Route::get('/get-contract-milestones/{id}',
        [\App\Http\Controllers\API\ContractMilestoneAPIController::class, 'getContractMilestones'])
        ->name('get contract milestones');
    Route::post('/milestone', [\App\Http\Controllers\API\ContractMilestoneAPIController::class, 'store'])
        ->name('store contract milestones');
    Route::put('/milestone/{id}', [\App\Http\Controllers\API\ContractMilestoneAPIController::class, 'update'])
        ->name('update contract milestones');
    Route::post('/get-contract-deliverables',
        [\App\Http\Controllers\API\ContractDeliverablesAPIController::class, 'getContractDeliverables'])
        ->name('get contract deliverables');
    Route::post('/get-contract-overall-retention-data',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getContractOverallRetentionData'])
        ->name('Contract Overall Retention Data');
    Route::post('/update-overall-retention',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'updateOverallRetention'])
        ->name('Update Overall Retention');
    Route::post('/deliverable', [\App\Http\Controllers\API\ContractDeliverablesAPIController::class, 'store'])
        ->name('store contract deliverable');
    Route::put('/deliverable/{id}', [\App\Http\Controllers\API\ContractDeliverablesAPIController::class, 'update'])
        ->name('update contract deliverable');
    Route::delete('/deliverable/{id}', [\App\Http\Controllers\API\ContractDeliverablesAPIController::class, 'destroy'])
        ->name('delete contract deliverable');
    Route::delete('/milestone/{id}', [\App\Http\Controllers\API\ContractMilestoneAPIController::class, 'destroy'])
        ->name('delete contract milestones');
Route::
    post('/milestone_retention',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'store'])
        ->name('store contract milestone retention');
    Route::
    post('/get-contract-milestone-retention-data',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'getContractMilestoneRetentionData'])
        ->name('Contract Milestone Retention Data');
    Route::
    post('/update-milestone-retention',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'updateMilestoneRetention'])
        ->name('Update Milestone Retention');
    Route::
    post('/update-retention-percentage',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'updateRetentionPercentage'])
        ->name('Update Retention Percentage');
    Route::
    delete('/delete-milestone-retention/{id}',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'destroy'])
        ->name('Delete Milestone Retention');
    Route::
    post('/export-milestone-retention',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'exportMilestoneRetention'])
        ->name('Export Milestone Retention');
    Route::post('/get-item-master-data', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getItemMasterFormData'])->name('Get item master data');
    Route::post('/get-all-assigned-items-by-company', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getAllAssignedItemsByCompany'])->name('Get all assigned items by company');
    Route::post('/get-boq-items-by-company', [\App\Http\Controllers\API\ContractBoqItemsAPIController::class, 'getBoqItemsByCompany'])->name('Get Boq items by company');
    Route::post('/update-boq-items-qty', [\App\Http\Controllers\API\ContractBoqItemsAPIController::class, 'updateBoqItemsQty'])->name('Update Boq items qty');
    Route::post('/copy-boq-items-qty', [\App\Http\Controllers\API\ContractBoqItemsAPIController::class, 'copyBoqItemsQty'])->name('Copy Boq items qty');
    Route::post('/add-boq-items', [\App\Http\Controllers\API\ContractBoqItemsAPIController::class, 'addTenderBoqItems'])->name('Add Boq items qty');
    Route::delete('/delete-boq-items/{id}', [\App\Http\Controllers\API\ContractBoqItemsAPIController::class, 'destroy'])->name('Delete Boq item');
    Route::post('/get-subcategories-by-main-category', [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getSubcategoriesByMainCategory'])->name('Get subcategories by main category');
    Route::post('/export-boq-items', [\App\Http\Controllers\API\ContractBoqItemsAPIController::class, 'exportBoqItems'])->name('Export Boq Items');
    Route::post('export-milestone', [
        \App\Http\Controllers\API\ContractMilestoneAPIController::class,
        'exportMilestone'
    ])->name('Export milestones');
    Route::post('export-deliverables', [
        \App\Http\Controllers\API\ContractDeliverablesAPIController::class,
        'exportDeliverables'
    ])->name('Export deliverables');
    Route::
    post('/milestone_retention',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'store'])
        ->name('store contract milestone retention');
    Route::
    post('/get-contract-milestone-retention-data',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'getContractMilestoneRetentionData'])
        ->name('Contract Milestone Retention Data');
    Route::
    post('/update-milestone-retention',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'updateMilestoneRetention'])
        ->name('Update Milestone Retention');
    Route::
    post('/update-retention-percentage',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'updateRetentionPercentage'])
        ->name('Update Retention Percentage');
    Route::
    delete('/delete-milestone-retention/{id}',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'destroy'])
        ->name('Delete Milestone Retention');
    Route::
    post('/export-milestone-retention',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'exportMilestoneRetention'])
        ->name('Export Milestone Retention');
    Route::
    post('/update-overall-retention',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'updateOverallRetention'])
        ->name('Update Overall Retention');

});
