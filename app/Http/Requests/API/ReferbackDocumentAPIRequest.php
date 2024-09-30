<?php

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;
class ReferbackDocumentAPIRequest extends APIRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'approvalLevelID' => 'required',
            'documentApprovedID' => 'required',
            'documentSystemID' => 'required',
            'referedbackComments' => 'required',
            'rollLevelOrder' => 'required',
        ];

        if (isset($this->input()['categoryId']) && ($this->input('categoryId') == 1 ||
                $this->input('categoryId') == 4 || $this->input('categoryId') == 6))
        {
            $rules ['contractHistoryUuid'] = 'required';
        } else
        {
            $rules ['contractUuid'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'approvalLevelID.required' => trans('common.approval_level_id_is_required'),
            'contractUuid.required' => trans('common.contract_id_is_required'),
            'contractHistoryUuid.required' => trans('common.contract_history_id_is_required'),
            'documentApprovedID.required' => trans('common.document_approved_id_is_required'),
            'documentSystemID.required' => trans('common.document_system_id_is_required'),
            'referedbackComments.required' => trans('common.referedback_comment_is_required'),
            'rollLevelOrder.required' => trans('common.roll_level_order_is_required'),
        ];
    }
}
