<?php

namespace App\Http\Requests\EventSerie;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\Base64Image;

class NewEventSerie extends FormRequest
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
			'name' => 'required|string|max:191',
			'cover' => new Base64Image,
			'description' => 'string|max:191',
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
