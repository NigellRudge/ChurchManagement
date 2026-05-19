<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Http\MaintenanceModeBypassCookie;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            LanguageSeeder::class,
            UserSeeder::class,
            ConfigSeeder::class,
            DistrictSeeder::class,
            CountrySeeder::class,
            CurrencySeeder::class,
            GenderSeed::class,
            MemberTypeSeeder::class,
            MembersSeeder::class,
            RelationShipTypeSeeder::class,
            EagleGroupSeeder::class,
            WorkGroupSeeder::class,
            SeedsTypesSeeder::class,
            BookConfigSeeder::class,
            BookCategorySeeder::class,
            MainAccountSeeder::class,
            SubAccountSeeder::class,
            ModuleSeeder::class
        ]);
    }
}
