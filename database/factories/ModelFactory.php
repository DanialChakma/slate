<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Carbon\Carbon;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'activated' => 1,
        'role_id' => 3,
        'department_id' => -1,
        'supervisor_id' => -1,
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Department::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'description' => $faker->text(300)
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Project::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text(300),
        'department_id' => -1
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Industry::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\ClientCompany::class, function (Faker\Generator $faker) {
    return [
        'company_name' => $faker->company,
        'remarks' => $faker->text(300),
        'industry_id' => -1
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\ClientCompanyContactPerson::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
		'designation' => $faker->text(10),
        'email' => $faker->companyEmail,
        'phone' => $faker->phoneNumber,
        'remarks' => $faker->text(300),
        'client_company_id' => -1
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Survey::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'remarks' => $faker->text(100),
        'department_id' => -1,
        'project_id' => -1
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Meeting::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
        'status' => 'Initiated',
        'remarks' => $faker->text(100),
        'project_id' => -1,
        'client_company_id' => -1,
        'survey_id' => -1,
        'start_time' => Carbon::now()->addDays(random_int(1, 10)),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Question::class, function (Faker\Generator $faker) {
    $types = collect([ 'Numeric', 'Non-Numeric']);
    return [
        'body' => $faker->name,
        'type' => $types->random(),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\AnswerOption::class, function (Faker\Generator $faker) {
    $body = $faker->text(6);
    return [
        'key' => substr($body,0,1),
        'body' => $body,
        'question_id' => -1,
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\MeetingSurveryResult::class, function (Faker\Generator $faker) {
    $body = $faker->text(6);
    return [
        'msisdn' => $faker->phoneNumber,
        'survey_id' => -1,
        'meeting_id' => -1,
        'question_id' => -1,
        'answer_option_id' => -1,
    ];
});
