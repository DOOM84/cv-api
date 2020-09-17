<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255'],
            'message' => 'required|string|max:2000|min:10'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required'),
            'name.string' => __('validation.string'),
            'name.max' => __('validation.custom.name.max'),
            'email.required' => __('validation.required'),
            'email.string' => __('validation.string'),
            'email.email' => __('validation.email'),
            'email.max' => __('validation.custom.email.max'),
            'message.required' => __('validation.required'),
            'message.string' => __('validation.string'),
            'message.max' => __('validation.custom.message.max'),
            'message.min' => __('validation.custom.message.min'),
        ];
    }
}
