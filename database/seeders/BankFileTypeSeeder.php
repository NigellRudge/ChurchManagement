<?php

namespace Database\Seeders;

use App\Models\BankFileType;
use Illuminate\Database\Seeder;

class BankFileTypeSeeder extends Seeder
{

    public function run()
    {
        BankFileType::insert([
            ['name' =>'DSB 10.22.55 SRD','currency_id' => 1 ],
            ['name' =>'DSB 10.34.72 USD','currency_id' => 2 ],
            ['name' =>'HKB 05.78.10 EUR','currency_id' => 3 ],
        ]);
    }
}
