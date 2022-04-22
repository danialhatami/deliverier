<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitDeliveryRequest extends FormRequest
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
            'sender_long' => ['required','numeric'],
            'sender_lat' => ['required','numeric'],
            'sender_address' => ['required', 'max:200'],
            'sender_name' => ['required'],
            'sender_mobile' => ['required'],
            'receiver_name' => ['required'],
            'receiver_mobile' => ['required', 'max:200'],
            'receiver_address' => ['required'],
            'receiver_long' => ['required','numeric'],
            'receiver_lat' => ['required','numeric']
        ];
    }
}
