<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('book_category')
            ->insert([
                ['name' => 'Fictional'],
                ['name' => 'Religious'],
                ['name' => 'Scientific'],
                ['name' => 'Mystery'],
            ]);
    }
}
