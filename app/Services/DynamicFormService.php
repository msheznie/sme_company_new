<?php

namespace App\Services;
 
use App\Models\FormSection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DynamicFormService
{
    public function __construct(){ }

    /**
     * get form generation details
     * @return Builder[]|Collection
     */
    public function getFormData($tenantId, $userId){
        $templateMasterID = GeneralService::getTemplateMaster($tenantId);
        return FormSection::with([
                'groups.controls.field.validators.validator',
                'groups.controls.field.options.option',
                'groups.controls.field.values' => function($query) use($tenantId, $userId) {
                     $query->where('user_id', $userId)
                        ->where('tenant_id', $tenantId);
                }
            ])
            ->where('template_master_id',$templateMasterID)
            ->where('status', 1)
            ->orderBy('sort', 'ASC')
            ->get();
    }
}