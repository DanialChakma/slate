<?php

use Illuminate\Database\Seeder;
use App\Department;
use App\Project;
use App\Question;

class SurveysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = Department::all();
        $questions = Question::all();
        $projects = Project::all();
        factory(App\Survey::class, 20)->make()->each(function ($survey) use($departments, $questions, $projects) {
            $department = $departments->random();
            $survey->department_id = $department->id;
            $survey->project_id = $projects->random()->id;
            $survey->save();

            $ids = [];
            for($p = 0; $p < random_int(2, 4); $p++){
                $q_id = $questions->random()->id;
                if(!in_array($q_id, $ids)){
                    $survey->questions()->attach($q_id);
                }
                array_push($ids,$q_id);
            }
        });
    }
}
