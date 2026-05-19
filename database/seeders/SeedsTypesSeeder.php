<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedsTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('seed_types')->insert([
           ['name' => 'Tide'],
           ['name' => 'Special Seed'],
           ['name' => 'Building Seed'],
        ]);
    }
}
