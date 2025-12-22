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
            'selectedCompanyID.required' => trans('common.company_id_is_required_dot'),
            'contract_id.required' => trans('common.contract_code_is_required_dot'),
            'title.required' => trans('common.title_is_required_dot'),
            'description.title' => trans('common.term_description_is_required_dot'),
        ];
    }
}
