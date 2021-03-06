<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:users,name,' . $this->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->id,
            'password' => 'confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required'),
            'name.max' => __('validation.custom.name.max'),
            'name.unique' => __('validation.custom.name.unique'),
            'name.string' => __('validation.string'),
            'email.required' => __('validation.required'),
            'email.string' => __('validation.string'),
            'email.email' => __('validation.email'),
            'email.max' => __('validation.custom.email.max'),
            'email.unique' => __('validation.custom.email.unique'),
            'password.confirmed' => __('validation.custom.password.confirmed'),
        ];
    }
}
