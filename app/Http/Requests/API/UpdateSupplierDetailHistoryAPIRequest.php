<?php

namespace App\Http\Requests\API;

use App\Models\SupplierDetailHistory;
use InfyOm\Generator\Request\APIRequest;

class UpdateSupplierDetailHistoryAPIRequest extends APIRequest
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
        $rules = SupplierDetailHistory::$rules;
        
        return $rules;
    }
}
