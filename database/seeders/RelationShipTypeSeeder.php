<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationShipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('relationship_types')
            ->insert([
                ['name' =>'Brother','code'=>"BRO",'trans_code'=>'brother'],
                ['name' =>'Sister','code'=>"SIS",'trans_code'=>'sister'],
                ['name' =>'Mother','code'=>"MOM",'trans_code'=>'mother'],
                ['name' =>'Father','code'=>"DAD",'trans_code'=>'father'],
                ['name' =>'Grand Father','code'=>"GDAD",'trans_code'=>'grand_father'],
                ['name' =>'Grand Mother','code'=>"GMON",'trans_code'=>'grand_mother'],
                ['name' =>'Cousin (female)','code'=>"CUZ",'trans_code'=>'cousin_female'],
                ['name' =>'Cousin (male)','code'=>"CUZ",'trans_code'=>'cousin_male'],
            ]);
    }
}
