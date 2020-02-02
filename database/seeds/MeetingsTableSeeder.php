<?php

use Illuminate\Database\Seeder;
use App\Project;
use App\User;
use App\ClientCompany;
use App\ClientCompanyContactPerson;
use App\Survey;

class MeetingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = Project::all();
        $clientCompanys = collect([]);
        $companys = ClientCompany::all();

        foreach ($companys as $clientCompany){
            if($clientCompany->clientCompanyContactPersons->count() > 0){
                $clientCompanys->push($clientCompany);
            }
        }

        $users = User::all();
        $surverys = Survey::all();
        factory(App\Meeting::class, 300)->make()->each(function ($meeting) use($clientCompanys, $projects, $users, $surverys) {
            $clientCompany = $clientCompanys->random();
            $project = $projects->random();

            $meeting->project_id = $project->id;
            $meeting->client_company_id = $clientCompany->id;
            $meeting->survey_id = $surverys->random()->id;
            $meeting->save();

            $ids = [];
            for($p = 0; $p < random_int(1, $clientCompany->clientCompanyContactPersons->count()); $p++){
                $cp_id = $clientCompany->clientCompanyContactPersons->random()->id;
                if(!in_array($cp_id, $ids)){
                    $meeting->clientCompanyContactPersons()->attach($cp_id);
                }
                array_push($ids,$cp_id);
            }

            $ids = [];
            for($p = 0; $p < random_int(1, 3); $p++){
                $u_id = $project->department->users->random()->id;

                if(!in_array($u_id, $ids)){
                    $meeting->staffs()->attach($u_id);
                }
                array_push($ids,$u_id);
            }
        });
    }
}
