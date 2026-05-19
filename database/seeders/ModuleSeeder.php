<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // inserting module for Members category
        DB::table('modules')->insert([
           ['name'=>'Leden','code'=>'M10','module_category' => config('constants.MODULE_CATEGORY_MEMBERS')],
           ['name'=>'Pasbekeerden','code'=>'M20','module_category' => config('constants.MODULE_CATEGORY_MEMBERS')],
           ['name'=>'Opgedragen kinderen','code'=>'M30','module_category' => config('constants.MODULE_CATEGORY_MEMBERS')],
           ['name'=>'Covid registratie','code'=>'M40','module_category' => config('constants.MODULE_CATEGORY_MEMBERS')],
           ['name'=>'Service Club','code'=>'M50','module_category' => config('constants.MODULE_CATEGORY_MEMBERS')],
           ['name'=>'Werkers en Bedieningen','code'=>'M60','module_category' => config('constants.MODULE_CATEGORY_GROUPS')],
           ['name'=>'Werkers presentielijsten','code'=>'M70','module_category' => config('constants.MODULE_CATEGORY_GROUPS')],
           ['name'=>'Eagle groepen','code'=>'M80','module_category' => config('constants.MODULE_CATEGORY_YOUTH')],
           ['name'=>'Eagle groepen presentielijsten','code'=>'M90','module_category' => config('constants.MODULE_CATEGORY_YOUTH')],
           ['name'=>'FT Visitors Lijsten','code'=>'M100','module_category' => config('constants.MODULE_CATEGORY_YOUTH')],
           ['name'=>'Jeugd Rapportages','code'=>'M110','module_category' => config('constants.MODULE_CATEGORY_YOUTH')],
           ['name'=>'Zaden','code'=>'M120','module_category' => config('constants.MODULE_CATEGORY_FINANCE')],
           ['name'=>'Offers','code'=>'M130','module_category' => config('constants.MODULE_CATEGORY_FINANCE')],
           ['name'=>'Transacties','code'=>'M140','module_category' => config('constants.MODULE_CATEGORY_FINANCE')],
           ['name'=>'Subrekeningen','code'=>'M150','module_category' => config('constants.MODULE_CATEGORY_FINANCE')],
           ['name'=>'Hoofdrekeningen','code'=>'M160','module_category' => config('constants.MODULE_CATEGORY_FINANCE')],
           ['name'=>'Begrotingen','code'=>'M170','module_category' => config('constants.MODULE_CATEGORY_FINANCE')],
           ['name'=>'Financiele Rapportages','code'=>'M180','module_category' => config('constants.MODULE_CATEGORY_FINANCE')],
           ['name'=>'Evenementen','code'=>'M190','module_category' => config('constants.MODULE_CATEGORY_EVENTS')],
           ['name'=>'Instellingen','code'=>'M200','module_category' => config('constants.MODULE_CATEGORY_CONFIG')],
           ['name'=>'BankFiles','code'=>'M210','module_category' => config('constants.MODULE_CATEGORY_FINANCE')],
        ]);

    }
}
