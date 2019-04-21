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

        Address::create([
			'street' => 'Rua Vicente Albertino Marchalek',
			'number' => '158',
			'zip_code' => '81250-690',
			'neighborhood' => 'Fazendinha',
			'complement' => null,
			'latitude' => uniqid(),
			'longitude' => uniqid(),
			'city_id' => 2878,
        ]);
    }
}
