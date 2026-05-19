<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status')->insert([
           ['name' => 'pending'],
           ['name' => 'promoted to member']
        ]);
        DB::table('marital_status')->insert([
           ['name' => 'married','trans_string'=>'married_label'],
           ['name' => 'single','trans_string'=>'single_label'],
           ['name' => 'divorced','trans_string'=>'divorced_label'],
           ['name' => 'concubine','trans_string'=>'concubine_label'],
           ['name' => 'widow','trans_string'=>'widow_label'],
           ['name' => 'widower','trans_string'=>'widower_label'],
        ]);

        DB::table('education')->insert([
            ['name' => 'Mulo'],
            ['name' => 'VWO'],
            ['name' => 'MBO'],
            ['name' => 'HBO'],
            ['name' => 'LBGO'],
            ['name' => 'LTS'],
            ['name' => 'GLOW'],
            ['name' => 'Universitair'],
        ]);

        DB::table('business_sectors')->insert([
            ['name' => 'Landbouw & Veeteelt'],
            ['name' => 'Horeca'],
            ['name' => 'ICT'],
            ['name' => 'Handel'],
            ['name' => 'Onderwijs'],
            ['name' => 'Verpleging'],
            ['name' => 'Finance'],
        ]);
    }
}
