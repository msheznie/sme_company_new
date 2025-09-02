<?php

namespace App\Http\Requests;

use App\Models\ERPLanguageMaster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Language;

class LanguageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'languageID' => [
                'required',
                Rule::exists(ERPLanguageMaster::class, 'languageID')->where(fn($query) => $query->where('isActive', 1)),
            ],
        ];
    }

    public function messages()
    {
        return [
            'languageID.required' => 'Language ID is required',
            'languageID.exists' => 'Selected language does not exist or is not active',
        ];
    }
}
