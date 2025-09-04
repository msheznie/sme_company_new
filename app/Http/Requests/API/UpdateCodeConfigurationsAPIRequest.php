<?php

namespace App\Http\Requests\API;

use App\Models\CodeConfigurations;
use Illuminate\Validation\Rule;
use InfyOm\Generator\Request\APIRequest;

class UpdateCodeConfigurationsAPIRequest extends APIRequest
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
                    ->where('company_system_id', $this->input('selectedCompanyID'))
                    ->where('uuid', $this->route('id'))
                    ->ignore($this->route('id'), 'uuid')
            ]
        ];
    }
    public function messages()
    {
        return [
            'code_pattern.required' => trans('common.code_pattern_is_required_dot'),
            'serialization_based_on.required' => trans('common.serialization_based_on_is_required_dot'),
            'serialization_based_on.unique' => trans('common.the_serialization_based_on_value_must_be_unique_dot')
        ];
    }
}
