<?php

use Illuminate\Database\Seeder;
use App\ClientCompany;

class ClientCompanyContactPeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clientCompanies = ClientCompany::all();
        factory(App\ClientCompanyContactPerson::class, 50)->make()->each(function ($clientCompanyContactPerson) use($clientCompanies) {
            $clientCompanyContactPerson->client_company_id = $clientCompanies->random()->id;
            $clientCompanyContactPerson->save();
        });
    }
}
