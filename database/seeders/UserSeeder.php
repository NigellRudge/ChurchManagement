<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')
            ->insert([
                [ 'name' => 'Administrator',
                  'password' => bcrypt('Admin@123'),
                  'email' => 'admin@gmail.com',
                  'language_id' => 1,
                    'is_admin' => true
                ]
            ]);
    }
}
