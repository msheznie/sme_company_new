<?php

namespace App\Http\Requests\API;

use App\Models\ContractPaymentTerms;
use InfyOm\Generator\Request\APIRequest;

class UpdateContractPaymentTermsAPIRequest extends APIRequest
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
            'uuid' => 'required',
            'value' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'selectedCompanyID.required' => 'Company ID is required.',
            'uuid.required' => 'Uuid is required.',
            'value.required' => 'Value is required.',
        ];
    }
}
