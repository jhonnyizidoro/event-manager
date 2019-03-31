<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{	
    protected $fillesble = [
		'hash', 'event_id', 'logo_id', 'signature_id', 'user_id'
	];
}
