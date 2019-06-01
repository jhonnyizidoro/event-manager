<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\Base64Image;

class NewEvent extends FormRequest
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
			'event.name' => 'required|string|max:191',
			'event.description' => 'string',
			'event.cover' => new Base64Image,
			'event.starts_at' => 'required|date_format:Y-m-d H:i',
			'event.ends_at' => 'required|date_format:Y-m-d H:i',
			'event.is_certified' => 'boolean',
			'event.min_age' => 'integer',
			'event.event_serie_id' => 'exists:event_series,id',
            'event.category_id' => 'required|exists:categories,id',
            'address.name' => 'required|string|max:191',
            'address.street' => 'required|string|max:191',
            'address.number' => 'required|numeric',
            'address.neighborhood' => 'required|string|max:191',
            'address.complement' => 'string|max:191',
            'address.zip_code' => 'required',
            'certificate.name' => 'string|max:191',
            'certificate.logo' => new Base64Image,
            'certificate.signature' => new Base64Image
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
