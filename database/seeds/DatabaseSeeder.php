<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DepartmentsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(IndustriesTableSeeder::class);
        $this->call(ClientCompaniesTableSeeder::class);
        $this->call(ClientCompanyContactPeopleTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(SurveysTableSeeder::class);
        $this->call(MeetingsTableSeeder::class);
        $this->call(AnswerOptionsTableSeeder::class);
        $this->call(MeetingSurveryResultsTableSeeder::class);
    }
}
