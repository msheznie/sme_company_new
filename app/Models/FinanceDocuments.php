<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class FinanceDocuments
 * @package App\Models
 * @version August 8, 2024, 2:17 pm +04
 *
 * @property string $uuid
 * @property integer $contract_id
 * @property boolean $document_type
 * @property integer $document_id
 * @property integer $document_system_id
 * @property integer $company_id
 * @property integer $created_by
 * @property integer $updated_by
 */
class FinanceDocuments extends Model
{
    use HasFactory;

    public $table = 'cm_finance_documents';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];
    protected $hidden = ['id', 'contract_id', 'company_id'];


    public $fillable = [
        'uuid',
        'contract_id',
        'document_type',
        'document_id',
        'document_system_id',
        'company_id',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'contract_id' => 'integer',
        'document_type' => 'boolean',
        'document_id' => 'integer',
        'document_system_id' => 'integer',
        'company_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public function invoiceMaster()
    {
        return $this->belongsTo(ErpBookingSupplierMaster::class, 'document_system_id', 'bookingSuppMasInvAutoID');
    }
    public function paymentVoucherMaster()
    {
        return $this->belongsTo(PaySpplierInvoiceMaster::class, 'document_system_id', 'PayMasterAutoId');
    }
    public function milestoneList()
    {
        return $this->hasMany(FinanceMilestoneDeliverable::class, 'finance_document_id', 'id');
    }
    public function deliverableList()
    {
        return $this->hasMany(FinanceMilestoneDeliverable::class, 'finance_document_id', 'id');
    }
    public function getExistingRecords($contractID, $documentType, $selectedCompanyID, $documentID)
    {
        return FinanceDocuments::where('contract_id', $contractID)
            ->where('document_id', $documentID)
            ->where('document_type', $documentType)
            ->where('company_id', $selectedCompanyID)
            ->pluck('document_system_id')
            ->toArray();
    }
    public function createFinanceDocuments($insertRec)
    {
        return FinanceDocuments::insert($insertRec);
    }
    public function deleteFinanceDocuments($toDelete, $contractId, $documentType, $documentID, $selectedCompanyID)
    {
        return FinanceDocuments::whereIn('document_system_id', $toDelete)
            ->where('contract_id', $contractId)
            ->where('document_type', $documentType)
            ->where('document_id', $documentID)
            ->where('company_id', $selectedCompanyID)
            ->delete();
    }
    public static function getContractFinanceDocument($contractID, $documentType, $documentID)
    {
        $financeDocument = FinanceDocuments::select('uuid', 'contract_id', 'document_type', 'document_id',
            'document_system_id')
            ->where('contract_id', $contractID)
            ->where('document_id', $documentID)
            ->where('document_type', $documentType)
            ->when($documentID == 4, function ($q)
            {
                $q->with([
                    'paymentVoucherMaster' => function ($q)
                    {
                        $q->select('PayMasterAutoId', 'BPVcode');
                    }
                ]);
            })->when($documentID == 11, function ($q)
            {
                $q->with([
                    'invoiceMaster' => function ($q)
                    {
                        $q->select('bookingSuppMasInvAutoID', 'bookingInvCode');
                    }
                ]);
            })
            ->get();
        $response = [];
        if(!empty($financeDocument))
        {
            foreach($financeDocument as $fDoc)
            {
                if($documentID == 4)
                {
                    if($fDoc['paymentVoucherMaster'])
                    {
                        $response[] = [
                            'id' => $fDoc['paymentVoucherMaster']['PayMasterAutoId'],
                            'itemName' => $fDoc['paymentVoucherMaster']['BPVcode']
                        ];
                    }
                } else
                {
                    if($fDoc['invoiceMaster'])
                    {
                        $response[] = [
                            'id' => $fDoc['invoiceMaster']['bookingSuppMasInvAutoID'],
                            'itemName' => $fDoc['invoiceMaster']['bookingInvCode']
                        ];
                    }
                }
            }
        }
        return $response;
    }
    public static function getSupplierInvoice($contractID, $companyId, $documentType, $documentId)
    {
        return FinanceDocuments::select('id', 'uuid', 'contract_id', 'document_system_id')
            ->with([
                'invoiceMaster' => function ($q)
                {
                    $q->select('bookingSuppMasInvAutoID', 'bookingAmountTrans', 'supplierTransactionCurrencyID',
                    'bookingInvCode', 'confirmedYN', 'approved', 'refferedBackYN', 'createdDateAndTime')
                    ->with([
                        'currency' => function ($q)
                        {
                            $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
                        }
                    ]);
                },
                'milestoneList' => function ($q)
                {
                    $q->where('document', 1)
                        ->select('finance_document_id', 'master_id')
                        ->with([
                            'milestone' => function ($q)
                            {
                                $q->select('id', 'uuid', 'title');
                            }
                        ]);
                },
                'deliverableList' => function ($q)
                {
                    $q->where('document', 2)
                        ->select('finance_document_id', 'master_id')
                        ->with([
                            'deliverable' => function ($q)
                            {
                                $q->select('id', 'uuid', 'title', 'milestoneID')
                                    ->with([
                                        'milestone' => function ($q)
                                        {
                                            $q->select('id', 'title');
                                        }
                                    ]);
                            }
                        ]);
                }
            ])

            ->where('document_type', $documentType)
            ->where('document_id', $documentId)
            ->where('contract_id', $contractID)
            ->where('company_id', $companyId)
            ->orderBy('document_system_id', 'desc')
            ->get();
    }


    public static function getPaymentVoucher($contractID, $companyId, $documentType, $documentId)
    {
        return FinanceDocuments::select('id', 'uuid', 'contract_id', 'document_system_id')
            ->with([
                'paymentVoucherMaster' => function ($q)
                {
                    $q->with([
                            'currency' => function ($q)
                            {
                                $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
                            },
                            'bankCurrency' => function ($q)
                            {
                                $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
                            },
                            'supplierCurrency'=> function ($q)
                            {
                                $q->select('currencyID', 'CurrencyCode', 'DecimalPlaces');
                            }
                        ]);
                },
                'milestoneList' => function ($q)
                {
                    $q->where('document', 1)
                        ->select('finance_document_id', 'master_id')
                        ->with([
                            'milestone' => function ($q)
                            {
                                $q->select('id', 'uuid', 'title');
                            }
                        ]);
                },
                'deliverableList' => function ($q)
                {
                    $q->where('document', 2)
                        ->select('finance_document_id', 'master_id')
                        ->with([
                            'deliverable' => function ($q)
                            {
                                $q->select('id', 'uuid', 'title', 'milestoneID')
                                    ->with([
                                        'milestone' => function ($q)
                                        {
                                            $q->select('id', 'title');
                                        }
                                    ]);
                            }
                        ]);
                }
            ])
            ->where('document_type', $documentType)
            ->where('document_id', $documentId)
            ->where('contract_id', $contractID)
            ->where('company_id', $companyId)
            ->orderBy('document_system_id', 'desc')
            ->get();
    }
    public static function checkFinanceDocumentExists($financeUuid)
    {
        return FinanceDocuments::select('id')->where('uuid', $financeUuid)->first();
    }
}







