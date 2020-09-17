<?php

namespace App\Http\Requests;

use App\Rules\BlockEmail;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:255|unique:users,name',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', new BlockEmail()],
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required'),
            'name.unique' => __('validation.custom.name.unique'),
            'name.max' => __('validation.custom.name.max'),
            'name.min' => __('validation.custom.name.min'),
            'name.string' => __('validation.string'),
            'email.required' => __('validation.required'),
            'email.string' => __('validation.string'),
            'email.email' => __('validation.email'),
            'email.max' => __('validation.custom.email.max'),
            'email.unique' => __('validation.custom.email.unique'),
            'password.required' => __('validation.required'),
            'password.string' => __('validation.string'),
            'password.min' => __('validation.min.numeric'),
            'password.confirmed' => __('validation.custom.password.confirmed'),
        ];
    }
}
