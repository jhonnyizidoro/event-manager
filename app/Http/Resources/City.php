<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class City extends JsonResource
{
    public function toArray($request)
    {
        return [
			'id' => $this->id,
			'name' => $this->name,
			'is_active' => $this->is_active,
			'state' => $this->state
		];
    }
}