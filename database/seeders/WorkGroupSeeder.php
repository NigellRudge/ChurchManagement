<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('work_groups')
            ->insert([
               ['name'=>'Mededelingen','pastor_id'=> 4,'coordinator_id'=>4],
               ['name'=>'Media Team','pastor_id'=> 4,'coordinator_id'=>3],
               ['name'=>'Protocol','pastor_id'=> 4,'coordinator_id'=>2],
               ['name'=>'Boekentafel','pastor_id'=> 4,'coordinator_id'=>2],
               ['name'=>'Kinder bediening','pastor_id'=> 4,'coordinator_id'=>2],
               ['name'=>'Worship Team','pastor_id'=> 4,'coordinator_id'=>2],
               ['name'=>'Sound Team','pastor_id'=> 4,'coordinator_id'=>2],
            ]);

        DB::table('work_group_memberships')
            ->insert([
               ['member_id'=>1,'group_id'=>2,'active'=>1,'join_date'=>Carbon::now(),'exit_date'=>null],
               ['member_id'=>2,'group_id'=>2,'active'=>1,'join_date'=>Carbon::now(),'exit_date'=>null],
               ['member_id'=>3,'group_id'=>2,'active'=>1,'join_date'=>Carbon::now(),'exit_date'=>null],
               ['member_id'=>1,'group_id'=>1,'active'=>1,'join_date'=>Carbon::now(),'exit_date'=>null],
               ['member_id'=>2,'group_id'=>1,'active'=>1,'join_date'=>Carbon::now(),'exit_date'=>null],
            ]);
    }
}
