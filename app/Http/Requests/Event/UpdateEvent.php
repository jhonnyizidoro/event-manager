<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\Base64Image;

class UpdateEvent extends FormRequest
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
			'event_id' => 'required|exists:events,id',
			'name' => 'string|max:191',
			'description' => 'string',
			'cover' => new Base64Image,
			'starts_at' => 'date_format:Y-m-d H:i',
			'ends_at' => 'date_format:Y-m-d H:i',
			'is_certified' => 'boolean',
			'min_age' => 'integer',
			'event_serie_id' => 'exists:event_series,id',
			'category_id' => 'exists:categories,id'
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
