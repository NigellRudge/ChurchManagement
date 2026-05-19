<?php

namespace Database\Seeders;

use App\Imports\MemberImport;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        Excel::import(new MemberImport(),'users_2.xls','public');
//        Excel::import(new MemberImport(),'users.xls','public');
        Excel::import(new MemberImport(),'users_total.xls','public');
    }
}
