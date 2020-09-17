<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPostRequest extends FormRequest
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
            'ru_title' => 'required',
            'en_title' => 'required',
            'ua_title' => 'required',
            'ru_subtitle' => 'required',
            'en_subtitle' => 'required',
            'ua_subtitle' => 'required',
            'body' => 'required',
            'ids' => 'required',
            'image' => [!$this->id ? 'required' : 'sometimes', 'image','mimes:jpeg,png,jpg,gif', 'max:5048']
        ];
    }

    public function messages()
    {
        return [
            'ru_title.required' => __('validation.required'),
            'en_title.required' => __('validation.required'),
            'ua_title.required' => __('validation.required'),
            'ru_subtitle.required' => __('validation.required'),
            'en_subtitle.required' => __('validation.required'),
            'ua_subtitle.required' => __('validation.required'),
            'ids.required' => __('validation.required'),
            'body.required' =>  __('validation.required'),
            'image.required' => __('validation.required'),
            'image.image' => __('validation.image'),
            'image.mimes' =>__('validation.mimes'),
            'image.max' => __('validation.max.file'),
        ];
    }
}
