<?php

namespace Database\Seeders;

use App\Imports\EagleMembershipsImport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EagleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('eagle_groups')
            ->insert([
                ['team_captain'=>91, 'name' => 'Steller Zee Arend'],
                ['team_captain'=>84, 'name' => 'Zwarte Kuif Arend'],
                ['team_captain'=>56, 'name' => 'Kroon Arend'],
                ['team_captain'=>45, 'name' => 'Golden Eagle'],
                ['team_captain'=>96, 'name' => 'Bald Eagle'],
                ['team_captain'=>6, 'name' => 'Keizer Arend'],
                ['team_captain'=>92, 'name' => 'Steen Arend'],
                ['team_captain'=>62, 'name' => 'Harpy Eagle'],
                ['team_captain'=>25, 'name' => 'Tawney Eagle'],
                ['team_captain'=>56, 'name' => 'Nog Geen Eagle Group'],
                ['team_captain'=>56, 'name' => 'Bezoekers Groep']
            ]);

        Excel::import(new EagleMembershipsImport(),'eagle_memberships.xlsx','public');
    }
}
