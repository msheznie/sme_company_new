<?php

namespace App\Http\Requests\API;

use App\Models\ContractPaymentTerms;
use InfyOm\Generator\Request\APIRequest;

class CreateContractPaymentTermsAPIRequest extends APIRequest
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
            'contract_id' => 'required',
            'title' => 'required',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' => 'Company ID is required.',
            'contract_id.required' => 'Contract Code is required.',
            'title.required' => 'Title is required.',
            'description.title' => 'Term Description is required.',
        ];
    }
}
