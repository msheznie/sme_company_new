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
    Route::post('/load-tender-data',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getSupplierTenderList'])
        ->name('Load tender data');
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
    Route::post('/check-milestone-retention',
        [\App\Http\Controllers\API\ContractMilestoneRetentionAPIController::class, 'checkMilestoneRetention'])
        ->name('check contract milestone retention');
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
    Route::post('contract-document-form-data', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'getContractDocumentFormData'
    ])->name('Contract document form data');
    Route::post('contract-document', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'store'
    ])->name('Store Contract Document');
    Route::post('contract-document-list', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'getContractDocumentList'
    ])->name('contract document list');
    Route::put('contract-document/{id}', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'update'
    ])->name('contract document update');
    Route::delete('contract-document/{id}', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'destroy'
    ])->name('contract document delete');
    Route::get('contract-document/{id}', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'show'
    ])->name('contract document show');
    Route::post('update-document-received', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'updateDocumentReceived'
    ])->name('update contract document received');
    Route::get('download-attachment', [
        \App\Http\Controllers\API\ErpDocumentAttachmentsAPIController::class,
        'downloadAttachment'
    ])->name('Download Contract Attachment');
    Route::post('update-document-return', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'updateDocumentReturn'
    ])->name('update contract document return');
    Route::post('additional-document-list', [
        \App\Http\Controllers\API\ContractAdditionalDocumentsAPIController::class,
        'getAdditionalDocumentList'
    ])->name('Contract Additional Document list');
    Route::post('additional-document', [
        \App\Http\Controllers\API\ContractAdditionalDocumentsAPIController::class,
        'store'
    ])->name('Store Additional Document');
    Route::put('additional-document/{id}', [
        \App\Http\Controllers\API\ContractAdditionalDocumentsAPIController::class,
        'update'
    ])->name('additional document update');
    Route::delete('additional-document/{id}', [
        \App\Http\Controllers\API\ContractAdditionalDocumentsAPIController::class,
        'destroy'
    ])->name('additional document delete');
    Route::post('additional-attachment', [
        \App\Http\Controllers\API\ContractAdditionalDocumentsAPIController::class,
        'addAdditionalAttachment'
    ])->name('Add Additional Document attachment');
    Route::get('additional-document/{id}', [
        \App\Http\Controllers\API\ContractAdditionalDocumentsAPIController::class,
        'show'
    ])->name('additional document show');

    Route::
    post('/get-document-master-data',
        [\App\Http\Controllers\API\DocumentMasterAPIController::class, 'getDocumentMasterData'])
        ->name('Document Master Data');
    Route::
    post('/document-master',
        [\App\Http\Controllers\API\DocumentMasterAPIController::class, 'store'])
        ->name('Create Document Master');
    Route::post('/document-status_update',
            [\App\Http\Controllers\API\DocumentMasterAPIController::class, 'documentStatusUpdate'])
            ->name('Update Document Status');
    Route::
    delete('/delete-document-master/{id}',
        [\App\Http\Controllers\API\DocumentMasterAPIController::class, 'destroy'])
        ->name('Delete Document Master');

    Route::
    post('/get-contract-confirmation-data',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getContractConfirmationData'])
        ->name('Contract Confirmation Data');
    Route::
        post('/confirm-contract',
            [\App\Http\Controllers\API\ContractMasterAPIController::class, 'confirmContract'])
            ->name('Contract Confirm');
    Route::post('/user-group', [
        \App\Http\Controllers\API\ContractUserGroupAPIController::class,
        'contractUserGroupList'
    ])->name('Contract user groups List');
    Route::post('/user-assign', [
        \App\Http\Controllers\API\ContractUserAssignAPIController::class,
        'store'
    ])->name('Contract assign user');
    Route::post('/get-assigned-user', [
        \App\Http\Controllers\API\ContractUserAssignAPIController::class,
        'getAssignedUsers'
    ])->name('Get contract assign user');
    Route::post('/delete-assigned-user', [
        \App\Http\Controllers\API\ContractUserAssignAPIController::class,
        'deleteAssignedUsers'
    ])->name('Delete contract assign user');
    Route::post('milestone-status_history', [
        \App\Http\Controllers\API\MilestoneStatusHistoryAPIController::class,
        'getMilestoneStatusHistory'
    ])->name('Milestone status history');

    Route::post('create-contract-history', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'createContractHistory'
    ])->name('Create Contract History');

    Route::post('get-category-wise-contract-data', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'getCategoryWiseContractData'
    ])->name('get Contract History Category Wise Data');
    Route::post('delete-contract-history', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'deleteContractHistory'
    ])->name('Delete Contract History');
    Route::post('get-contract-history', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'getContractHistory'
    ])->name('Get Contract History');
    Route::post('update-contract-status', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'updateContractStatus'
    ])->name('Update Contract Status');
    Route::post('get-contract-view-data', [
        \App\Http\Controllers\API\ContractMasterAPIController::class,
        'getContractViewData'
    ])->name('get contract view data');
    Route::post('update-extend-contract-status', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'updateExtendStatus'
    ])->name('Update Extend Contract Status');
    Route::post('contract-history-attachments', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'contractHistoryAttachments'
    ])->name('Contract History Attachments');
    Route::post('get-contract-history-attachments', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'getContractHistoryAttachments'
    ])->name('Get Contract History Attachments');
    Route::post('delete-contract-history-attachment', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'deleteHistoryAttachment'
    ])->name('Delete Contract History Attachments');
    Route::post('contract-history-delete', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'contractHistoryDelete'
    ])->name('Contract History Delete');
    Route::post('periodic-billing-form-data', [
        \App\Http\Controllers\API\PeriodicBillingsAPIController::class,
        'periodicBillingFormData'
    ])->name('Payment schedule form data');
    Route::post('periodic-billing', [
        \App\Http\Controllers\API\PeriodicBillingsAPIController::class,
        'store'
    ])->name('store periodic billing');
    Route::put('periodic-billing/{id}', [
        \App\Http\Controllers\API\PeriodicBillingsAPIController::class,
        'update'
    ])->name('update periodic billing');

    Route::post('get-contract-list-status', [
        \App\Http\Controllers\API\contractStatusHistoryAPIController::class,
        'getContractListStatus'
    ])->name('Contract History Status');
    Route::post('payment-schedule-form-data', [
        \App\Http\Controllers\API\MilestonePaymentSchedulesAPIController::class,
        'getPaymentScheduleFormData'
    ])->name('get payment schedule form data');
    Route::post('milestone-payment-schedule', [
        \App\Http\Controllers\API\MilestonePaymentSchedulesAPIController::class,
        'store'
    ])->name('store milestone payment schedule');
    Route::post('milestone-payment-schedule-list', [
        \App\Http\Controllers\API\MilestonePaymentSchedulesAPIController::class,
        'getMilestonePaymentSchedules'
    ])->name('get milestone payment schedule list');

    Route::put('milestone-payment-schedule/{id}', [
        \App\Http\Controllers\API\MilestonePaymentSchedulesAPIController::class,
        'update'
    ])->name('update milestone payment schedule');

    Route::post('get-time-consumption-form-data', [
        \App\Http\Controllers\API\TimeMaterialConsumptionAPIController::class,
        'getTimeConsumptionFormData'
    ])->name('time and material consumption from data');

    Route::post('time-material-consumption', [
        \App\Http\Controllers\API\TimeMaterialConsumptionAPIController::class,
        'store'
    ])->name('store time and material consumption');

    Route::post('all-time-material-consumption', [
        \App\Http\Controllers\API\TimeMaterialConsumptionAPIController::class,
        'getAllTimeMaterialConsumption'
    ])->name('get all time and material consumption');

    Route::put('time-material-consumption/{id}', [
        \App\Http\Controllers\API\TimeMaterialConsumptionAPIController::class,
        'update'
    ])->name('update time and material consumption');

    Route::delete('time-material-consumption/{id}', [
        \App\Http\Controllers\API\TimeMaterialConsumptionAPIController::class,
        'destroy'
    ])->name('delete time and material consumption');

    Route::post('pull-items-from-boq', [
        \App\Http\Controllers\API\TimeMaterialConsumptionAPIController::class,
        'pullItemsFromBOQ'
    ])->name('pull items from boq');

    Route::delete('milestone-payment-schedule/{id}', [
        \App\Http\Controllers\API\MilestonePaymentSchedulesAPIController::class,
        'destroy'
    ])->name('delete milestone payment schedule');
    Route::get('/get-contract-payment-terms/{id}',
        [\App\Http\Controllers\API\ContractPaymentTermsAPIController::class, 'getContractPaymentTerms'])
        ->name('get contract payment terms');
    Route::post('payment-term',
        [\App\Http\Controllers\API\ContractPaymentTermsAPIController::class, 'store'])
        ->name('create payment term');
    Route::put('update-payment-term', [
        \App\Http\Controllers\API\ContractPaymentTermsAPIController::class,
        'update'
    ])->name('update payment term');
    Route::delete('delete-payment-term/{id}', [
        \App\Http\Controllers\API\ContractPaymentTermsAPIController::class,
        'destroy'
    ])->name('delete payment term');

    Route::post('get-reminder-configuration', [
        \App\Http\Controllers\API\CMContractReminderScenarioAPIController::class,
        'showReminders'
    ])->name('Contract Reminder Configuration');
    Route::post('get-reminder-drop-val', [
        \App\Http\Controllers\API\CMContractReminderScenarioAPIController::class,
        'showRemindersDropValues'
    ])->name('Contract Reminder Dropdown Values');
    Route::post('crate-reminder-configuration', [
        \App\Http\Controllers\API\CMContractReminderScenarioAPIController::class,
        'createReminderConfiguration'
    ])->name('Create Contract Reminder Configuration');

    Route::post('get-contract-master-data', [
        \App\Http\Controllers\API\CMContractMasterAmdAPIController::class,
        'getContractMasterData'
    ])->name('Contract History Status');

    Route::post('update-contract-status-amendment', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'updateContractStatusAmendment'
    ])->name('Update Contract Status Amendment');

    Route::post('delete-contract-amend-boq', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'deleteContractBoqAmend'
    ])->name('Delete Contract Boq Amend');

    Route::post('get-milestone-amend', [
        \App\Http\Controllers\API\CMContractMileStoneAmdAPIController::class,
        'getMilestoneAmend'
    ])->name('get Milestone Amend');


    Route::post('delete-milestone-amd', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'deleteMilestoneAmd'
    ])->name('Delete Contract Boq Amend');

    Route::post('delete-deliverables-amd', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'deleteContractDeliverablesAmd'
    ])->name('Delete Contract Deliverables Amend');

    Route::post('contract-document-amd', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'contractDocumentAmd'
    ])->name('Get Amend Contract Documents');

    Route::get('download-attachment-amd', [
        \App\Http\Controllers\API\ErpDocumentAttachmentsAPIController::class,
        'downloadAttachmentAmd'
    ])->name('Download Contract Attachment');

    Route::post('get-active-amd-sections', [
        \App\Http\Controllers\API\ContractAmendmentAreaAPIController::class,
        'getActiveAmdSections'
    ])->name('get Milestone Amend');

    Route::post('get-contract-amd-history', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'getContractAmdHistory'
    ])->name('get Amendment history');

    Route::post('confirm-contract-amendment', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'confirmContractAmendment'
    ])->name('Confirm Amendment');

    Route::post('additional-document-amendment', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'additionalDocumentAmendment'
    ])->name('Additional Document Amendment');
    Route::post('overall-penalty-data', [
        \App\Http\Controllers\API\ContractOverallPenaltyAPIController::class,
        'overallPenaltyData'
    ])->name('Overall penalty data');
    Route::post('overall-penalty', [
        \App\Http\Controllers\API\ContractOverallPenaltyAPIController::class,
        'store'
    ])->name('store overall penalty');
    Route::put('overall-penalty/{id}', [
        \App\Http\Controllers\API\ContractOverallPenaltyAPIController::class,
        'update'
    ])->name('update overall penalty');
    Route::post('update-penalty-status', [
        \App\Http\Controllers\API\ContractOverallPenaltyAPIController::class,
        'updatePenaltyStatus'
    ])->name('update penalty status');

    Route::post('get-contract-document-uuid', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'getContractDocumentByUuid'
    ])->name('Contract Document Data');

    Route::post('get-contract-document-path', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'getContractDocumentPath'
    ])->name('Contract Document Path');

    Route::post('delete-document-tracing', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'deleteDocumentTracing'
    ])->name('Delete Contract Document');

    Route::post('update-contract-document', [
        \App\Http\Controllers\API\ContractDocumentAPIController::class,
        'updateContractDocument'
    ])->name('Update Contract Document');

    Route::post('get-additional-document-uuid', [
        \App\Http\Controllers\API\ContractAdditionalDocumentsAPIController::class,
        'getAdditionalDocumentByUuid'
    ])->name('Contract Additional Document Data');

    Route::post('delete-contract-document-attachment', [
        \App\Http\Controllers\API\ContractAdditionalDocumentsAPIController::class,
        'deleteContractDocumentAttachment'
    ])->name('Delete Contract Document');

    Route::post('update-additional-document', [
        \App\Http\Controllers\API\ContractAdditionalDocumentsAPIController::class,
        'updateAdditionalDoc'
    ])->name('Update additional Document');
    Route::post('milestone-penalty-master-data', [
        \App\Http\Controllers\API\ContractMilestonePenaltyMasterAPIController::class,
        'milestonePenaltyMasterData'
    ])->name('Milestone penalty master data');
    Route::post('milestone-penalty-master', [
        \App\Http\Controllers\API\ContractMilestonePenaltyMasterAPIController::class,
        'store'
    ])->name('store milestone penalty master');
    Route::put('milestone-penalty-master/{id}', [
        \App\Http\Controllers\API\ContractMilestonePenaltyMasterAPIController::class,
        'update'
    ])->name('update milestone penalty master');
    Route::post('milestone-penalty-details', [
        \App\Http\Controllers\API\ContractMilestonePenaltyDetailAPIController::class,
        'milestonePenaltyDetails'
    ])->name('Milestone penalty detail');
    Route::post('milestone-penalty-detail', [
        \App\Http\Controllers\API\ContractMilestonePenaltyDetailAPIController::class,
        'store'
    ])->name('store milestone penalty detail');
    Route::post('milestone-penalty-detail-form-data', [
        \App\Http\Controllers\API\ContractMilestonePenaltyDetailAPIController::class,
        'getMilestonePenaltyDetailFormData'
    ])->name('get milestone penalty detail form data');
    Route::post('get_milestone-amount', [
        \App\Http\Controllers\API\ContractMilestonePenaltyDetailAPIController::class,
        'getMilestoneAmount'
    ])->name('get milestone amount');
    Route::put('milestone-penalty-detail/{id}', [
        \App\Http\Controllers\API\ContractMilestonePenaltyDetailAPIController::class,
        'update'
    ])->name('update milestone penalty detail');
    Route::delete('milestone-penalty-detail/{id}', [
        \App\Http\Controllers\API\ContractMilestonePenaltyDetailAPIController::class,
        'destroy'
    ])->name('delete milestone penalty detail');
    Route::post('update-milestone-penalty-status', [
        \App\Http\Controllers\API\ContractMilestonePenaltyDetailAPIController::class,
        'updateMilestonePenaltyStatus'
    ])->name('update milestone penalty status');
    Route::post('/export-milestone-penalty',
        [\App\Http\Controllers\API\ContractMilestonePenaltyDetailAPIController::class,
            'exportMilestonePenalty'])
        ->name('Export milestone penalty');

    Route::post('get-contract-status-history', [
        \App\Http\Controllers\API\ContractHistoryAPIController::class,
        'getContractStatusHistory'
    ])->name('Get Contract History');

    Route::get('dashboard-total-contracts', [
        \App\Http\Controllers\API\DashboardController::class,
        'getTotalContracts'
    ])->name('Get Total Contracts');

    Route::get('dashboard-contract-type-active-contracts', [
        \App\Http\Controllers\API\DashboardController::class,
        'getContractTypeWiseActiveContracts'
    ])->name('Get Contract Type Wise Active Contracts');

    Route::post('dashboard-contract-expiry', [
        \App\Http\Controllers\API\DashboardController::class,
        'getContractExpiry'
    ])->name('Get Expiry Contract');

    Route::post('dashboard-contract-milestone', [
        \App\Http\Controllers\API\DashboardController::class,
        'getContractMilestone'
    ])->name('Get Milestone Contract');

    Route::post('dashboard-total-contract-status', [
        \App\Http\Controllers\API\DashboardController::class,
        'getTotalContractStatus'
    ])->name('Get Total Contract Status');

    Route::post('/get-contract-master-graph',
        [\App\Http\Controllers\API\ContractMasterAPIController::class, 'getContractMasterResultsForGraph'])
        ->name('Contract Master Graph');

    Route::post('/export-contract-status-history',
        [\App\Http\Controllers\API\contractStatusHistoryAPIController::class, 'exportContractStatusHistory'])
        ->name('Export Contract Status History');

    Route::post('/finance-document-filters',
        [\App\Http\Controllers\API\FinanceDocumentsAPIController::class, 'getFinanceDocumentFilters'])
        ->name('Get finance document filters');

    Route::post('/pull-finance-document-from-erp',
        [\App\Http\Controllers\API\FinanceDocumentsAPIController::class, 'store'])
        ->name('Pull finance document from erp');

    Route::post('/get-contract-invoices',
        [\App\Http\Controllers\API\FinanceDocumentsAPIController::class, 'getContractInvoices'])
        ->name('Get contract invoices');

    Route::post('/get-contract-payment-voucher',
        [\App\Http\Controllers\API\FinanceDocumentsAPIController::class, 'getContractPaymentVoucher'])
        ->name('Get contract payment voucher');
});



