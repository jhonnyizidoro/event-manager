<?php

use Illuminate\Database\Seeder;
use App\Models\Reaction;

class ReactionsTableSeeder extends Seeder
{
	private $reactions = [
		[
			'name' => 'Curtir',
			'icon' => 'icon-like'
		],
		[
			'name' => 'Amei',
			'icon' => 'icon-heart'
		],
		[
			'name' => 'Haha',
			'icon' => 'icon-laugh'
		],
		[
			'name' => 'Uau',
			'icon' => 'icon-wow'
		],
		[
			'name' => 'Triste',
			'icon' => 'icon-sad'
		],
		[
			'name' => 'Grr',
			'icon' => 'icon-angry',
		],
	];

    public function run()
    {
        foreach ($this->reactions as $reaction) {
			Reaction::create([
				'name' => $reaction['name'],
				'icon' => $reaction['icon'],
			]);
		}
    }
}
