<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('book_status')
            ->insert([
                ['name' =>'Available','code'=>''],
                ['name' =>'Lent','code'=>''],
                ['name' =>'Reserved','code'=>'']
            ]);

        DB::table('book_condition')
            ->insert([
               ['name' => 'New', 'code' => 'New'],
               ['name' => 'Used-Good', 'code' => 'Used-G'],
               ['name' => 'Used-Bad', 'code' => 'Used-B'],
               ['name' => 'Written Off', 'code' => 'W-OFF'],
            ]);
    }
}
