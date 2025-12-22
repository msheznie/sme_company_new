<?php

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;

class ContractConfirmRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return  [
            'contractUuid' => 'required',
            'documentSystemID' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'contractUuid.required' => trans('common.contract_code_is_required'),
            'documentSystemID.required' => trans('common.document_system_id_is_required')
        ];
    }
}
