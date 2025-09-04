<?php

namespace App\Http\Requests\API;

use App\Models\ContractAdditionalDocuments;
use InfyOm\Generator\Request\APIRequest;

class CreateContractAdditionalDocumentsAPIRequest extends APIRequest
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
        return [
            'contractUUid' => 'required',
            'documentTypes' => 'required',
            'documentName' => 'required',
            'selectedCompanyID' => 'required',
            'file' => 'required'
        ];
    }
    public function messages(){
        return [
            'contractUUid.required' => trans('common.contract_code_is_required'),
            'documentTypes.required' => trans('common.document_type_is_required'),
            'selectedCompanyID.required' => trans('common.company_id_is_required'),
             'documentName.required' => trans('common.document_name_is_required_dot'),
             'file.required' => trans('common.file_is_required_dot'),
        ];
    }
}
