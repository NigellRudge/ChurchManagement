<?php

namespace Database\Seeders;

use App\Models\MainAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MainAccount::insert([
            ['name' => 'Inkomsten SRD', 'currency_id' => 1, 'description' => 'Alle SRD inkomsten van de organisatie', 'account_type' => config('constants.MAIN_ACCOUNT_TYPE_INCOME')],
            ['name' => 'Uitgaven SRD', 'currency_id' => 1, 'description' => 'Alle SRD uitgaven van de organisatie', 'account_type' => config('constants.MAIN_ACCOUNT_TYPE_EXPENSE')],

            ['name' => 'Inkomsten USD', 'currency_id' => 2, 'description' => 'Alle USD inkomsten van de organisatie', 'account_type' => config('constants.MAIN_ACCOUNT_TYPE_INCOME')],
            ['name' => 'Uitgaven USD', 'currency_id' => 2, 'description' => 'Alle USD uitgaven van de organisatie', 'account_type' => config('constants.MAIN_ACCOUNT_TYPE_EXPENSE')],

            ['name' => 'Inkomsten EUR', 'currency_id' => 3, 'description' => 'Alle EUR inkomsten van de organisatie', 'account_type' => config('constants.MAIN_ACCOUNT_TYPE_INCOME')],
            ['name' => 'Uitgaven EUR', 'currency_id' => 3, 'description' => 'Alle EUR uitgaven van de organisatie', 'account_type' => config('constants.MAIN_ACCOUNT_TYPE_EXPENSE')],
        ]);
    }
}
