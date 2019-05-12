<?php

namespace App\Http\Requests\UserProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\Base64Image;

class UpdateUserProfile extends FormRequest
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
			'cover' => new Base64Image,
			'piture' => new Base64Image,
			'description' => 'string|max:191',
			'custom_url' => 'string|max:25'
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
