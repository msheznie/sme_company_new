<?php

namespace App\Http\Requests\API;

use App\Models\ContractMaster;
use InfyOm\Generator\Request\APIRequest;

class UpdateContractMasterAPIRequest extends APIRequest
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
            'counterPartyName' => 'required',
            'primaryEmail' => 'nullable',
            'secondaryEmail' => 'nullable|different:primaryEmail',
        ];
    }

    public function messages()
    {
        return [
            'counterPartyName.required' => trans('common.counter_party_name_is_required'),
            'secondaryEmail.different' => trans('common.counter_party_email_validation'),
        ];
    }
}
