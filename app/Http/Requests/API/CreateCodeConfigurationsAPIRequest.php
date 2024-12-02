<?php

namespace App\Http\Requests\API;

use App\Models\CodeConfigurations;
use InfyOm\Generator\Request\APIRequest;
use Illuminate\Validation\Rule;

class CreateCodeConfigurationsAPIRequest extends APIRequest
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
            'code_pattern' => 'required',
            'serialization_based_on' => ['required',
                Rule::unique('cm_code_configuration')
                    ->where('company_system_id', $this->input('selectedCompanyID'))]
        ];
    }

    public function messages()
    {
        return [
            'code_pattern.required' => 'Code pattern is required.',
            'serialization_based_on.required' => 'Serialization based on is required.',
            'serialization_based_on.unique' => 'The serialization based on value must be unique'
        ];
    }
}
