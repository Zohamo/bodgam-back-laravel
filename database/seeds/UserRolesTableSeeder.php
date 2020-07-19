<?php

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    public $timestamps = false;
}

use Illuminate\Database\Seeder;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => 1, 'role' => 'SUPER_ADMIN'],
            ['id' => 2, 'role' => 'ADMIN'],
            ['id' => 3, 'role' => 'EDITOR'],
            ['id' => 4, 'role' => 'AUTHOR'],
            ['id' => 5, 'role' => 'CONTRIBUTOR'],
            ['id' => 6, 'role' => 'SUBSCRIBER']
        ];

        foreach ($roles as $role) {
            UserRole::updateOrCreate(['id' => $role['id']], $role);
        }
    }
}
