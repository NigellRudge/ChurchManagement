<?php

namespace Database\Seeders;

use App\Models\CurrencyHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            ['name'=>'Surinamese Dollars','code'=>'SRD','exchange_rate'=>1.00],
            ['name'=>'United States Dollars','code'=>'USD','exchange_rate'=>20],
            ['name'=>'Euro','code'=>'EUR','exchange_rate'=>21.50],
        ]);

        CurrencyHistory::insert([
            ['currency_id' => '2','rate' =>20 ,'start_date'=>now()->toDateString(),],
            ['currency_id' => '3','rate' => 21.50,'start_date'=>now()->toDateString()]
        ]);
    }
}
