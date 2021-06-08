<?php

namespace App\Http\Requests\Bus;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'plate_num' => ['required', Rule::unique('users')->ignore($this->route('bus'))],
        ];
    }

    public function messages()
    {
        return [
            'plate_num.unique' => 'this plate number is taken',
        ];
    }
}
