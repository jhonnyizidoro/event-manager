<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	public $timestamps = false;

    protected $fillable = [
		'name', 'is_active'
    ];

    public function users_interested()
    {
        return $this->belongsToMany('App\Models\User', 'user_interests');
	}
	
	public static function mostPopular($count = 5)
	{
		$usersInterests = [];
		foreach (Self::get() as $key => $category) {
			$usersInterests[] = (object) [
				'category' => $category->name,
				'count' => sizeof($category->users_interested)
			];
		}
		usort($usersInterests, 'orderByCount');
		return array_slice($usersInterests, 0, $count);
	}
}
