<?php

use Illuminate\Database\Seeder;
use App\Department;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = Department::all();
        factory(App\Project::class, 50)->make()->each(function ($project) use($departments) {
            $project->department_id = $departments->random()->id;
            $project->save();
        });
    }
}
