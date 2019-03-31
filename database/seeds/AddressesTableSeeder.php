<?php

use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::create([
			'street' => 'Adão Sobocinski',
			'number' => '161',
			'zip_code' => '80050-480',
			'neighborhood' => 'Cristo Rei',
			'complement' => 'AP601',
			'latitude' => uniqid(),
			'longitude' => uniqid(),
			'city_id' => 2878,
		]);
    }
}
