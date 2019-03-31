<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
			'name' => 'Jhonny ADMIN Menarim',
			'email' => 'admin@gmail.com',
			'password' => bcrypt('123456'),
			'nickname' => 'jhonnymenarim',
			'birthdate' => '1997-01-06',
			'is_admin' => true,
			'address_id' => 1
		]);

		$user = User::create([
			'name' => 'Jhonny USER Menarim',
			'email' => 'user@gmail.com',
			'password' => bcrypt('123456'),
			'nickname' => 'jhonnymenarim',
			'birthdate' => '1997-01-06',
			'address_id' => 1
		]);
    }
}
