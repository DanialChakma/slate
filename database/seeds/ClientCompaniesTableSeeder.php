<?php

use Illuminate\Database\Seeder;
use App\Industry;

class ClientCompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = Industry::all();
        factory(App\ClientCompany::class, 50)->make()->each(function ($clientCompany) use($industries) {
            $clientCompany->industry_id = $industries->random()->id;
            $clientCompany->save();
        });
    }
}
