<?php

namespace App\Http\Requests;

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
            'name' => ['required', 'max:100'],
            'national_code' => ['max:100'],
            'company_name' => ['max:100'],
            'webhook_url' => ['max:100'],
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required'],
            'password_confirmed' => ['required', 'same:password'],
        ];
    }
}
