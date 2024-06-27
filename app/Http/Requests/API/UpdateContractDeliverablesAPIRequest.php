<?php

namespace App\Http\Requests\API;

use App\Models\ContractDeliverables;
use InfyOm\Generator\Request\APIRequest;

class UpdateContractDeliverablesAPIRequest extends APIRequest
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
            'companySystemID' => 'required',
            'contractUuid' => 'required',
            'uuid' => 'required',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'companySystemID.required' => 'Company ID is required',
            'contractUuid.required' => 'Contract ID is required',
            'uuid.required' => 'Deliverable ID is required',
            'description.required' => 'Description is required',
        ];
    }
}
