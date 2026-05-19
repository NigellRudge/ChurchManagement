<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            ['name' => 'Suriname','code' => 'SUR'],
            ['name' => 'United States','code'=>'US'],
            ['name' => 'Netherlands','code'=>'NED'],
            ['name' => 'Guyana','code'=>'GUY'],
            ['name' => 'Brazil','code'=>'BRAZ'],
        ]);
    }
}
