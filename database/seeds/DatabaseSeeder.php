<?php

use Illuminate\Database\Seeder;
use ProfileRatingsTypesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ProfileRatingsTypesTableSeeder::class);
    }
}
