<?php

namespace App\Http\Requests\API;

use App\Models\ContractMilestone;
use Illuminate\Validation\Rule;
use InfyOm\Generator\Request\APIRequest;

class CreateContractMilestoneAPIRequest extends APIRequest
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
            'formData' => 'required',
            'companySystemID' => 'required',
            'contractUuid' => 'required',
            'formData.title' => 'required',
            'formData.description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'formData.required' => 'Fill all required fields',
            'companySystemID.required' => 'Company Id is required.',
            'contractUuid.required' => 'Contract ID is required.',
            'formData.title.required' => 'Milestone Title is required.',
            'formData.description.required' => 'Description is required.',
        ];
    }
}
