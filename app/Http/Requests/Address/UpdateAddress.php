<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateAddress extends FormRequest
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
			'name' => 'string:max:255',
			'street' => 'string:max:255',
			'number' => 'string:max:255',
			'zip_code' => 'regex:/[0-9]{5}-[0-9]{3}/',
			'neighborhood' => 'string:max:255',
			'complement' => 'string:max:255',
			'city_id' => 'exists:cities,id',
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
