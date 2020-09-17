<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProjectRequest extends FormRequest
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
            'ru_details' => 'required',
            'en_details' => 'required',
            'ua_details' => 'required',
            'ids' => 'required',
            'image' => [!$this->id ? 'required' : 'sometimes', 'image','mimes:jpeg,png,jpg,gif', 'max:5048']
        ];
    }

    public function messages()
    {
        return [
            'ru_name.required' => __('validation.required'),
            'en_name.required' => __('validation.required'),
            'ua_name.required' => __('validation.required'),
            'ru_details.required' => __('validation.required'),
            'en_details.required' => __('validation.required'),
            'ua_details.required' => __('validation.required'),
            'ids.required' => __('validation.custom.ids.required'),
            'image.required' => __('validation.required'),
            'image.image' => __('validation.image'),
            'image.mimes' =>__('validation.mimes'),
            'image.max' => __('validation.max.file'),
        ];
    }
}
