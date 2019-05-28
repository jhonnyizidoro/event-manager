<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		$this->command->info('Seeding: StatesTableSeeder');
        DB::unprepared(file_get_contents(__DIR__ . '/SQL/StatesTableSeeder.sql'));

		$this->command->info('Seeding: CitiesTableSeeder');
		DB::unprepared(file_get_contents(__DIR__ . '/SQL/CitiesTableSeeder.sql'));

		$this->call(CategoriesTableSeeder::class);
		$this->call(ReactionsTableSeeder::class);
		$this->call(AddressesTableSeeder::class);
		$this->call(UsersTableSeeder::class);
		$this->call(EventsTableSeeder::class);
    }
}
