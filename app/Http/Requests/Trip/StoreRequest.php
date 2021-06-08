<?php

namespace App\Http\Requests\Trip;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'source' => 'required',
            'destination' => 'required',
            'attend_time' => 'required',
            'leave_time' => 'required',
            'ticket' => 'required|numeric',
            'seats' => 'required|numeric',
            'bus_id' => 'required|exists:buses,id',
        ];
    }
}
