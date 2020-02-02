<?php

namespace App\Providers;
use App\Department;
use App\Meeting;
use App\Policies\DepartmentPolicy;
use App\Policies\MeetingPolicy;
use App\Policies\SurveyPolicy;
use App\Policies\QuestionPolicy;
use App\Policies\UserPolicy;
use App\Project;
use App\Policies\ProjectPolicy;
use App\Industry;
use App\Policies\IndustryPolicy;
use App\ClientCompany;
use App\Policies\ClientCompanyPolicy;
use App\ClientCompanyContactPerson;
use App\Policies\ClientCompanyContactPersonPolicy;

use App\Question;
use App\Survey;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Project::class => ProjectPolicy::class,
        Department::class => DepartmentPolicy::class,
        Industry::class => IndustryPolicy::class,
        ClientCompany::class => ClientCompanyPolicy::class,
        ClientCompanyContactPerson::class => ClientCompanyContactPersonPolicy::class,
        Meeting::class => MeetingPolicy::class,
        Survey::class => SurveyPolicy::class,
        Question::class => QuestionPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
