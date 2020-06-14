<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'abbr' => 'required|string|max:10',
            'direction' => 'required|in:rtl,ltr',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'in' => 'القيم المدخلة غير صحيحة',
            'name.string' => 'اسم اللغة لابد ان يكون احرف',
            'name.max' => 'اسم اللغة لابد ان لا يزيد عن 100 حرف',
            'abbr.string' => 'الاختصار لابد ان يكون احرف',
            'abbr.max' => 'اسم اللغة لابد ان لا يزيد عن 10 حرف',
        ];
    }
}
