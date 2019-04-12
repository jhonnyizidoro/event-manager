<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateUser extends FormRequest
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
			'user_id' => 'required|exists:users,id',
			'name' => 'string|max:191',
			'email' => 'email|max:191|unique:users',
			'password' => 'confirmed|string|max:59|min:6',
			'nickname' => 'string|max:191',
			'birthdate' => 'date|date_format:d/m/Y',
			'is_admin' => 'boolean',
			//Preferences
			'receive_events_email' => 'boolean',
			'receive_events_notification' => 'boolean',
			'events_notification_range' => 'integer|min:0',
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
