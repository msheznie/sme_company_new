<?php

namespace App\Http\Requests\API;

use App\Models\ContractAdditionalDocuments;
use InfyOm\Generator\Request\APIRequest;

class UpdateContractAdditionalDocumentsAPIRequest extends APIRequest
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
            'uuid' => 'required',
            'selectedCompanyID' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'uuid.required' => trans('common.contract_document_id_not_found'),
            'selectedCompanyID.required' => trans('common.company_id_is_required')
        ];
    }
}
