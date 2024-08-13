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
            'selectedCompanyID.required' => 'Company Id is required.',
            'contractUuid.required' => 'Contract ID is required.',
            'documentType.required' => 'Document type is required.',
            'selectedType.required' => 'Selected type is required.',
        ];
    }
}
