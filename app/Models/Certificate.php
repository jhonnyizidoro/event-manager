<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
		'logo', 'signature_name', 'signature_image', 'event_id'
	];

	public function getSignatureImageAttribute($image)
    {
		if ($image) {
			return env('AWS_URL') . $image;
		}
	}

	public function getLogoAttribute($image)
    {
		if ($image) {
			return env('AWS_URL') . $image;
		}
	}
}
