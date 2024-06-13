<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'common'], function ()
{
    Route::post('document-attachments', [
        \App\Http\Controllers\API\ErpDocumentAttachmentsAPIController::class,
        'getDocumentAttachments'
    ])->name('Document Attachment');
});
