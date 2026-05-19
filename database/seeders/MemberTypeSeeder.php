<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('member_types')->insert([
            ['name'=>'Gemeente Lid','code'=>'GMLD'],
            ['name'=>'Diaken','code'=>'DKN'],
            ['name'=>'Pastor','code'=>'PAST'],
            ['name'=>'Ouederling','code'=>'DLNG'],
            ['name'=>'Bezoeker','code'=>'BZKR'],
            ['name'=>'JW Team Captain','code'=>'TC'],
            ['name'=>'Bischop','code'=>'Bshp'],
        ]);
    }
}
