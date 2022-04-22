<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TraceRequest extends FormRequest
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
            'delivery_request_id',
            'delivery_man_lat' => ['required'],
            'delivery_man_long' => ['required'],
        ];
    }
}
