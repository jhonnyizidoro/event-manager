<?php

namespace App\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class NewCity extends FormRequest
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
			'name' => 'required|max:255|string',
			'state_id' => 'required|exists:states,id'
        ];
	}
	
	protected function failedValidation(Validator $validator)
    {
		$response = [
			'status' => false,
			'errors' => $validator->errors(),
		];
		throw new HttpResponseException(response()->json($response, 403));
    }
}
