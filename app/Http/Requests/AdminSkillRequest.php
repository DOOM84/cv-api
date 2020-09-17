<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminSkillRequest extends FormRequest
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
            'ru_name' => 'required',
            'en_name' => 'required',
            'ua_name' => 'required',
            'rate' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'ru_name.required' => __('validation.required'),
            'en_name.required' => __('validation.required'),
            'ua_name.required' => __('validation.required'),
            'rate.required' => __('validation.required'),
            'rate.integer' => __('validation.integer'),
        ];
    }
}
