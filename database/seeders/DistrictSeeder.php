<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('districts')->insert([
            ['name'=>'Paramaribo','code'=>'PARBO'],
            ['name'=>'Commewijne','code'=>'COMW'],
            ['name'=>'Para','code'=>'PARA'],
            ['name'=>'Wanica','code'=>'WAN'],
            ['name'=>'Brokopondo','code'=>'BRKDO'],
            ['name'=>'Moengo','code'=>'MNGO'],
            ['name'=>'Sipaliwini','code'=>'SWINI'],
            ['name'=>'Saramacca','code'=>'SARMCA'],
            ['name'=>'Coronie','code'=>'CORN'],
            ['name'=>'Nickerie','code'=>'NICK'],
        ]);
    }
}
