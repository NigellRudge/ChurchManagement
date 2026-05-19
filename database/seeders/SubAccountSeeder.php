<?php

namespace Database\Seeders;

use App\Models\SubAccount;
use Illuminate\Database\Seeder;

class SubAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubAccount::insert([
            ['name' =>'EBS', 'description' => 'Alle uitgaven gerelateerd aan EBS', 'parent_account_id'=> 2, 'can_delete' => false],
            ['name' =>'SWM', 'description' => 'Alle uitgaven gerelateerd aan SWM', 'parent_account_id'=> 2, 'can_delete' => false],

            ['name' =>'Telesur', 'description' => 'Alle uitgaven gerelateerd aan Telesur (telefoon en internet)', 'parent_account_id'=> 2, 'can_delete' => false],
            ['name' =>'AC onderhoud', 'description' => 'Alle uitgaven gerelateerd aan het onderhouden van de verschillende Airco\'s', 'parent_account_id'=> 2, 'can_delete' => false],

            ['name' =>'Aanschaf apparatuur', 'description' => 'Alle uitgaven gerelateerd aan het kopen van nieuwe apparatuur', 'parent_account_id'=> 2, 'can_delete' => false],
            ['name' =>'TV Programmas', 'description' => 'Alle uitgaven gerelateerd aan de verschillende TV programmas', 'parent_account_id'=> 2, 'can_delete' => false],
            ['name' =>'Sociaal werk', 'description' => 'Alle uitgaven gerelateerd aan sociale doeleinden', 'parent_account_id'=> 2, 'can_delete' => false],

            ['name' =>'Tienden', 'description' => 'Alle SRD tienden', 'parent_account_id'=> 1, 'can_delete' => false],
            ['name' =>'Zaden', 'description' => 'Alle SRD Zaden', 'parent_account_id'=> 1, 'can_delete' => false],

            ['name' =>'Tienden', 'description' => 'Alle USD tienden', 'parent_account_id'=> 3, 'can_delete' => false],
            ['name' =>'Zaden', 'description' => 'Alle USD Zaden', 'parent_account_id'=> 3, 'can_delete' => false],

            ['name' =>'Tienden', 'description' => 'Alle EUR tienden', 'parent_account_id'=> 5, 'can_delete' => false],
            ['name' =>'Zaden', 'description' => 'Alle EUR zaden', 'parent_account_id'=> 5, 'can_delete' => false],

            ['name' =>'Offers', 'description' => 'Alle SRD offers', 'parent_account_id'=> 1, 'can_delete' => false],
            ['name' =>'Offers', 'description' => 'Alle USD offers', 'parent_account_id'=> 3, 'can_delete' => false],
            ['name' =>'Offers', 'description' => 'Alle EUR offers', 'parent_account_id'=> 5, 'can_delete' => false],

            ['name' =>'Bouwfonds', 'description' => 'Alle SRD zaden bestemd voor de bouw', 'parent_account_id'=> 1, 'can_delete' => false],
            ['name' =>'Bouwfonds', 'description' => 'Alle USD zaden bestemd voor de bouw', 'parent_account_id'=> 3, 'can_delete' => false],
            ['name' =>'Bouwfonds', 'description' => 'Alle EUR zaden bestemd voor de bouw', 'parent_account_id'=> 5, 'can_delete' => false],

            ['name' =>'Bouw werkzaamheden Florastraat', 'description' => 'Alle SRD uitgaven bestemd voor de bouw aan de florastraat', 'parent_account_id'=> 2, 'can_delete' => false],
            ['name' =>'Bouw werkzaamheden Florastraat', 'description' => 'Alle USD uitgaven bestemd voor de bouw aan de florastraat', 'parent_account_id'=> 4, 'can_delete' => false],
            ['name' =>'Bouw werkzaamheden Florastraat', 'description' => 'Alle EUR uitgaven bestemd voor de bouw aan de florastraat', 'parent_account_id'=> 6, 'can_delete' => false],

        ]);
    }
}
