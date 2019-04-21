<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
			'name' => 'Jhonny ADMIN Menarim',
			'email' => 'admin@gmail.com',
			'password' => '123456',
			'nickname' => 'jhonnymenarimadmin',
			'birthdate' => '1997-01-06',
			'is_admin' => true,
			'address_id' => 1
		]);

		$user = User::create([
			'name' => 'Jhonny USER Menarim',
			'email' => 'user@gmail.com',
			'password' => '123456',
			'nickname' => 'jhonnymenarimuser',
			'birthdate' => '1997-01-06',
			'address_id' => 1
		]);

		$user = User::create([
			'name' => 'Matheus Xavier',
			'email' => 'matheus@email.com',
			'password' => '123456',
			'nickname' => 'Matheus',
			'birthdate' => '1997-10-31',
			'is_admin' => true,
			'address_id' => 2
		]);

		$profile = UserProfile::create([
			'user_id' => $user->id
		]);
    }
}
