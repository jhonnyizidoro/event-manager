<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{	
    protected $fillesble = [
		'hash', 'logo', 'signature_name', 'signature_image', 'event_id'
	];
}
