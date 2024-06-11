<?php

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;
class RejectDocumentAPIRequest extends APIRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return  [
            'approvalLevelID' => 'required',
            'contractUuid' => 'required',
            'documentApprovedID' => 'required',
            'documentSystemID' => 'required',
            'rejectedComments' => 'required',
            'rollLevelOrder' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'approvalLevelID.required' => trans('common.approval_level_id_is_required'),
            'contractUuid.required' => trans('common.contract_id_is_required'),
            'documentApprovedID.required' => trans('common.document_approved_id_is_required'),
            'documentSystemID.required' => trans('common.document_system_id_is_required'),
            'rejectedComments.required' => trans('common.document_system_id_is_required'),
            'rollLevelOrder.required' => trans('common.roll_level_order_is_required'),
        ];
    }
}
