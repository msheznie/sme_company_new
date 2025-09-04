<?php

namespace App\Http\Requests\API;

use App\Models\FinanceDocuments;
use InfyOm\Generator\Request\APIRequest;

class CreateFinanceDocumentsAPIRequest extends APIRequest
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
            'selectedCompanyID' => 'required',
            'contractUuid' => 'required',
            'documentType' => 'required',
            'selectedType' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' => trans('common.company_id_is_required_dot'),
            'contractUuid.required' => trans('common.contract_code_is_required_dot'),
            'documentType.required' => trans('common.document_type_is_required_dot'),
            'selectedType.required' => trans('common.selected_type_is_required_dot'),
        ];
    }
}
