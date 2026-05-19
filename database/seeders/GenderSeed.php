<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genders')
            ->insert([
                ['name' => 'male','code'=>'M','trans_string' => 'gender_male_label'],
                ['name' => 'female','code'=>'F','trans_string' => 'gender_female_label'],
            ]);
    }
}
