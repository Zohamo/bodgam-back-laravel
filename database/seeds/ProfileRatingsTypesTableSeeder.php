<?php

use Illuminate\Database\Eloquent\Model;

class ProfileRatingsType extends Model
{
    public $timestamps = false;
}

use Illuminate\Database\Seeder;

/**
 * ProfileRatingsTypes Table Seeder
 */
class ProfileRatingsTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['id' => 1, 'type' => 'reception'],
            ['id' => 2, 'type' => 'teaching'],
            ['id' => 3, 'type' => 'liking'],
            ['id' => 4, 'type' => 'humility'],
            ['id' => 5, 'type' => 'honesty'],
            ['id' => 6, 'type' => 'speed']
        ];

        foreach ($types as $type) {
            ProfileRatingsType::updateOrCreate(['id' => $type['id']], $type);
        }
    }
}
