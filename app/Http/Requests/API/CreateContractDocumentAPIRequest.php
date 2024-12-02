<?php

namespace App\Http\Requests\API;

use App\Models\ContractDocument;
use InfyOm\Generator\Request\APIRequest;

class CreateContractDocumentAPIRequest extends APIRequest
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
        $rules = [
            'contractUUid' => 'required',
            'documentTypes' => 'required',
            'selectedCompanyID' => 'required',
            'documentName' => 'required'
        ];

        if ($this->input('followingRequest'))
        {
            $rules['attachedDate'] = 'required';
        }


        return $rules;
    }

    public function messages()
    {
        return [
            'contractUUid.required' => trans('common.contract_code_is_required'),
            'documentTypes.required' => trans('common.document_type_is_required'),
            'selectedCompanyID.required' => trans('common.company_id_is_required'),
            'attachedDate.required' => 'Due Date is required',
            'documentName.required' => 'Document Name is required',
        ];
    }
}
