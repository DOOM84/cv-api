<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvatarRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024'
        ];
    }

    public function messages()
    {
        return [
            'image.required' => __('validation.required'),
            'image.image' => __('validation.image'),
            'image.mimes' =>__('validation.mimes'),
            'image.max' => __('validation.max.file'),
        ];
    }
}
