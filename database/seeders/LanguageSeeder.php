<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            [ 'name' =>'English','code'=>'en', 'icon_name' =>'en-flag.gif','trans_code' => 'english'],
            [ 'name' =>'Nederlands','code'=>'nl', 'icon_name' =>'ned-flag.gif','trans_code' => 'dutch']
        ]);
    }
}
