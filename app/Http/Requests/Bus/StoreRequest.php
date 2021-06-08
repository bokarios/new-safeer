<?php

namespace App\Http\Requests\Bus;

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
            'name' => 'required',
            'plate_num' => 'required|unique:buses,plate_num',
            'company_id' => 'required|exists:companies,id',
        ];
    }

    public function messages()
    {
        return [
            'plate_num.unique' => 'this plate number is taken',
        ];
    }
}
