<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminTrainingRequest extends FormRequest
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
            'ru_description' => 'required',
            'en_description' => 'required',
            'ua_description' => 'required',
            'year' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'ru_name.required' => __('validation.required'),
            'en_name.required' => __('validation.required'),
            'ua_name.required' => __('validation.required'),
            'ru_description.required' => __('validation.required'),
            'en_description.required' => __('validation.required'),
            'ua_description.required' => __('validation.required'),
            'year.required' => __('validation.required'),
            'year.integer' => __('validation.integer'),
        ];
    }
}
