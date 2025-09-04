<?php

namespace App\Http\Requests\API;

use App\Models\TimeMaterialConsumption;
use InfyOm\Generator\Request\APIRequest;

class CreateTimeMaterialConsumptionAPIRequest extends APIRequest
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
        ];
    }
    public function messages()
    {
        return [
            'selectedCompanyID.required' => trans('common.company_id_is_required_dot'),
            'contract_id.required' => trans('common.contract_code_is_required_dot')
        ];
    }
}
