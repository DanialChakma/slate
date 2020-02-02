<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/oauth', 'GraphLoginController@oauth')->name('oauth');

Route::get('/smsDeliveryReceipt', 'SmsDeliveryReceiptController@smsDeliveryReceipt')->name('smsDeliveryReceipt');
Route::post('/answerCallback', 'SmsDeliveryReceiptController@answerCallback')->name('answerCallback');
Route::get('/answerCallback', 'SmsDeliveryReceiptController@answerCallback')->name('answerCallback');
Route::get('/callback', 'SmsDeliveryReceiptController@callback')->name('callback');


Route::group(['middleware' => ['auth']], function () {

    Route::get('/', function () {
        if( auth()->user()->isAdmin() ){
            $PendingSmsCount = \App\MeetingSurveryResult::where('failed_for_no_balance',true)->count();
            $hasPendingSms = $PendingSmsCount > 0 ? true:false;
            if( $hasPendingSms ){
                Session::put('pending_sms_msg','*** Info: Please refill your account at SMS Gateway and refresh this page, some Survey SMSes are pending.***');
            }
        }
        return view('welcome');
    });

    Route::get('/home', function () {
        return view('welcome');
    })->name('home');

    
    Route::get('/users', 'UserController@index')->name('users')->middleware('can:viewList,App\User');
    Route::get('/users/index', 'UserController@index')->name('users.index')->middleware('can:viewList,App\User');
    Route::get('/users/show/{user}', 'UserController@show')->name('users.show')->middleware('can:view,user');
    Route::get('/users/create', 'UserController@create')->name('users.create')->middleware('can:create,App\User');
    Route::post('/users/store', 'UserController@store')->name('users.store')->middleware('can:create,App\User');
    Route::get('/users/edit/{user}', 'UserController@edit')->name('users.edit')->middleware('can:update,user');
    Route::get('/users/delete/{user}', 'UserController@delete')->name('users.delete')->middleware('can:delete,user');
    Route::post('/users/confirmDelete/{user}', 'UserController@confirmDelete')->name('users.confirmDelete')->middleware('can:delete,user');
    Route::post('/users/confirmDeleteAjax/{user}', 'UserController@confirmDeleteAjax')->name('users.confirmDeleteAjax')->middleware('can:delete,user');
    Route::post('/users/update/{user}', 'UserController@update')->name('users.update')->middleware('can:update,user');
    Route::post('/users/updatePassword/{user}', 'UserController@updatePassword')->name('users.updatePassword')->middleware('can:passwordUpdateForAdmin,user');
    Route::get('/users/userChangePassword/{user}', 'UserController@userChangePasswordView')->name('users.userChangePasswordView')->middleware('can:update,user');
    Route::post('/users/userChangePassword/{user}', 'UserController@userChangePassword')->name('users.userChangePassword')->middleware('can:update,user');

    Route::get('/projects', 'ProjectController@index')->name('projects')->middleware('can:viewList,App\Project');
	Route::get('/projects/search', 'ProjectController@search')->name('projects.search')->middleware('can:viewList,App\Project');
    Route::get('/projects/index', 'ProjectController@index')->name('projects.index')->middleware('can:viewList,App\Project');
    Route::get('/projects/show/{project}', 'ProjectController@show')->name('projects.show')->middleware('can:view,project');
    Route::get('/projects/create', 'ProjectController@create')->name('projects.create')->middleware('can:create,App\Project');
    Route::post('/projects/store', 'ProjectController@store')->name('projects.store')->middleware('can:create,App\Project');
    Route::get('/projects/edit/{project}', 'ProjectController@edit')->name('projects.edit')->middleware('can:update,project');
    Route::post('/projects/update/{project}', 'ProjectController@update')->name('projects.update')->middleware('can:update,project');
    Route::get('/projects/delete/{project}', 'ProjectController@delete')->name('projects.delete')->middleware('can:delete,project');
    Route::post('/projects/confirmDelete/{project}', 'ProjectController@confirmDelete')->name('projects.confirmDelete')->middleware('can:delete,project');
    Route::post('/projects/confirmDeleteAjax/{project}', 'ProjectController@confirmDeleteAjax')->name('projects.confirmDeleteAjax')->middleware('can:delete,project');


    //Department CRUD work done by danial
    Route::get('/departments', 'DepartmentController@index')->name('departments')->middleware('can:viewList,App\Department');
    Route::get('/departments/index', 'DepartmentController@index')->name('departments.index')->middleware('can:viewList,App\Department');
    Route::get('/departments/show/{department}', 'DepartmentController@show')->name('departments.show')->middleware('can:view,department');
    Route::get('/departments/create', 'DepartmentController@create')->name('departments.create')->middleware('can:create,App\Department');
    Route::post('/departments/store', 'DepartmentController@store')->name('departments.store')->middleware('can:create,App\Department');
    Route::get('/departments/edit/{department}', 'DepartmentController@edit')->name('departments.edit')->middleware('can:update,department');
    Route::post('/departments/update/{department}', 'DepartmentController@update')->name('departments.update')->middleware('can:update,department');
    Route::get('/departments/delete/{department}', 'DepartmentController@delete')->name('departments.delete')->middleware('can:delete,department');
    Route::post('/departments/confirmDelete/{department}', 'DepartmentController@confirmDelete')->name('departments.confirmDelete')->middleware('can:delete,department');
    Route::post('/departments/confirmDeleteAjax/{department}', 'DepartmentController@confirmDeleteAjax')->name('departments.confirmDeleteAjax')->middleware('can:delete,department');

    //Industry CRUD work done by danial
    Route::get('/industries', 'IndustryController@index')->name('industries')->middleware('can:viewList,App\Industry');
    Route::get('/industries/search', 'IndustryController@search')->name('industries.search')->middleware('can:viewList,App\Industry');
    Route::get('/industries/index', 'IndustryController@index')->name('industries.index')->middleware('can:viewList,App\Industry');
    Route::get('/industries/show/{industry}', 'IndustryController@show')->name('industries.show')->middleware('can:view,industry');
    Route::get('/industries/create', 'IndustryController@create')->name('industries.create')->middleware('can:create,App\Industry');
    Route::post('/industries/store', 'IndustryController@store')->name('industries.store')->middleware('can:create,App\Industry');
    Route::get('/industries/edit/{industry}', 'IndustryController@edit')->name('industries.edit')->middleware('can:update,industry');
    Route::post('/industries/update/{industry}', 'IndustryController@update')->name('industries.update')->middleware('can:update,industry');
    Route::get('/industries/delete/{industry}', 'IndustryController@delete')->name('industries.delete')->middleware('can:delete,industry');
    Route::post('/industries/confirmDelete/{industry}', 'IndustryController@confirmDelete')->name('industries.confirmDelete')->middleware('can:delete,industry');
    Route::post('/industries/confirmDeleteAjax/{industry}', 'IndustryController@confirmDeleteAjax')->name('industries.confirmDeleteAjax')->middleware('can:delete,industry');

    //Client Company CRUD Operation done by danial
    Route::get('/clientCompanies', 'ClientCompanyController@index')->name('clientCompanies')->middleware('can:viewList,App\ClientCompany');
    Route::get('/clientCompanies/search', 'ClientCompanyController@search')->name('clientCompanies.search')->middleware('can:viewList,App\ClientCompany');
    Route::get('/clientCompanies/index', 'ClientCompanyController@index')->name('clientCompanies.index')->middleware('can:viewList,App\ClientCompany');
    Route::get('/clientCompanies/show/{clientCompany}', 'ClientCompanyController@show')->name('clientCompanies.show')->middleware('can:view,clientCompany');
    Route::get('/clientCompanies/create', 'ClientCompanyController@create')->name('clientCompanies.create')->middleware('can:create,App\ClientCompany');
    Route::post('/clientCompanies/store', 'ClientCompanyController@store')->name('clientCompanies.store')->middleware('can:create,App\ClientCompany');
    Route::get('/clientCompanies/edit/{clientCompany}', 'ClientCompanyController@edit')->name('clientCompanies.edit')->middleware('can:update,clientCompany');
    Route::post('/clientCompanies/update/{clientCompany}', 'ClientCompanyController@update')->name('clientCompanies.update')->middleware('can:update,clientCompany');
    Route::get('/clientCompanies/delete/{clientCompany}', 'ClientCompanyController@delete')->name('clientCompanies.delete')->middleware('can:delete,clientCompany');
    Route::post('/clientCompanies/confirmDelete/{clientCompany}', 'ClientCompanyController@confirmDelete')->name('clientCompanies.confirmDelete')->middleware('can:delete,clientCompany');
    Route::post('/clientCompanies/confirmDeleteAjax/{clientCompany}', 'ClientCompanyController@confirmDeleteAjax')->name('clientCompanies.confirmDeleteAjax')->middleware('can:delete,clientCompany');

    // Client Company's Contact Person CRUD Operation done by danial.
    Route::get('/clientCompaniesContacts/{clientCompany}', 'ClientCompanyContactPersonController@index')->name('clientCompaniesContacts')->middleware('can:viewList,App\ClientCompanyContactPerson');
    Route::post('/clientCompaniesContacts/search', 'ClientCompanyContactPersonController@search')->name('clientCompaniesContacts.search')->middleware('can:viewList,App\ClientCompanyContactPerson');
    Route::get('/clientCompaniesContacts/index/{clientCompany}', 'ClientCompanyContactPersonController@index')->name('clientCompaniesContacts.index')->middleware('can:viewList,App\ClientCompanyContactPerson');
    Route::get('/clientCompaniesContacts/{clientCompany}', 'ClientCompanyContactPersonController@index')->name('clientCompaniesContacts.index')->middleware('can:viewList,App\ClientCompanyContactPerson');
    Route::get('/clientCompaniesContacts/show/{clientCompanyContactPerson}', 'ClientCompanyContactPersonController@show')->name('clientCompaniesContacts.show')->middleware('can:view,clientCompanyContactPerson');
    Route::get('/clientCompaniesContacts/edit/{clientCompanyContactPerson}', 'ClientCompanyContactPersonController@edit')->name('clientCompaniesContacts.edit')->middleware('can:update,clientCompanyContactPerson');
    Route::get('/clientCompaniesContacts/create/{clientCompany}', 'ClientCompanyContactPersonController@create')->name('clientCompaniesContacts.create')->middleware('can:create,App\ClientCompanyContactPerson');
    Route::post('/clientCompaniesContacts/store', 'ClientCompanyContactPersonController@store')->name('clientCompaniesContacts.store')->middleware('can:create,App\ClientCompanyContactPerson');
    Route::post('/clientCompaniesContacts/update/{clientCompanyContactPerson}', 'ClientCompanyContactPersonController@update')->name('clientCompaniesContacts.update')->middleware('can:update,clientCompanyContactPerson');
    Route::get('/clientCompaniesContacts/delete/{clientCompanyContactPerson}', 'ClientCompanyContactPersonController@delete')->name('clientCompaniesContacts.delete')->middleware('can:delete,clientCompanyContactPerson');
    Route::post('/clientCompaniesContacts/confirmDelete/{clientCompanyContactPerson}', 'ClientCompanyContactPersonController@confirmDelete')->name('clientCompaniesContacts.confirmDelete')->middleware('can:delete,clientCompanyContactPerson');

    // Meeting CRUD Operation Routes done by danial.
    Route::get('/meetings', 'MeetingController@index')->name('meetings')->middleware('can:viewList,App\Meeting');
    Route::get('/meetings/search', 'MeetingController@search')->name('meetings.search')->middleware('can:viewList,App\Meeting');
    Route::get('/meetings/index', 'MeetingController@index')->name('meetings.index')->middleware('can:viewList,App\Meeting');
    Route::get('/meetings/show/{meeting}', 'MeetingController@show')->name('meetings.show')->middleware('can:view,meeting');
    Route::get('/meetings/create', 'MeetingController@create')->name('meetings.create')->middleware('can:create,App\Meeting');
    Route::post('/meetings/store', 'MeetingController@store')->name('meetings.store')->middleware('can:create,App\Meeting');
    Route::get('/meetings/edit/{meeting}', 'MeetingController@edit')->name('meetings.edit')->middleware('can:update,meeting');
    Route::post('/meetings/update/{meeting}', 'MeetingController@update')->name('meetings.update')->middleware('can:update,meeting');
    Route::get('/meetings/delete/{meeting}', 'MeetingController@delete')->name('meetings.delete')->middleware('can:delete,meeting');
    Route::post('/meetings/confirmDelete/{meeting}', 'MeetingController@confirmDelete')->name('meetings.confirmDelete')->middleware('can:delete,meeting');
    Route::post('/meetings/confirmDeleteAjax/{meeting}', 'MeetingController@confirmDeleteAjax')->name('meetings.confirmDeleteAjax')->middleware('can:delete,meeting');
    Route::get('/meetings/getProjectUnderDepartment', 'MeetingController@getProjectUnderDepartment')->name('meetings.getProjectUnderDepartment')->middleware('can:create,App\Meeting');
    Route::get('/meetings/getContactsUnderCompany', 'MeetingController@getContactsUnderCompany')->name('meetings.getContactsUnderCompany')->middleware('can:create,App\Meeting');
    Route::get('/meetings/getContactDetails', 'MeetingController@getContactDetails')->name('meetings.getContactDetails')->middleware('can:create,App\Meeting');
    Route::get('/meetings/mymeetings', 'MeetingController@meetingsOfFieldStuffs')->name('meetings.meetingsOfFieldStuffs')->middleware('can:viewList,App\Meeting');
    Route::get('/meetings/changeStatus/{meeting}', 'MeetingController@changeStatus')->name('meetings.changeStatus')->middleware('can:view,meeting');
    Route::get('/meetings/meetingCompleteAction', 'MeetingController@meetingCompleteAction')->name('meetings.meetingCompleteAction');
    Route::get('/meetings/meetingRescheduleAction', 'MeetingController@meetingRescheduleAction')->name('meetings.meetingRescheduleAction');
    Route::get('/meetings/meetingCancelAction', 'MeetingController@meetingCancelAction')->name('meetings.meetingCancelAction');
    Route::get('meetings/getMeetings','MeetingController@getMeetings')->name('meetings.getMeetings');
    Route::get('meetings/getMeetingsPerDay','MeetingController@get_meetings_per_day')->name('meetings.getMeetingsPerDay');

    Route::get('meetings/toggleNotification','HomeController@toggleNotificationBox')->name('meetings.toggleNotification');
    Route::get('meetings/getUpcomingMeetings','MeetingController@getUpcomingMeetings')->name('meetings.getUpcomingMeetings');
    Route::get('/meetings/listAccount', 'MeetingController@listAccount')->name('meetings.listAccount');
    Route::post('/meetings/listAccount', 'MeetingController@listAccount')->name('meetings.listAccount');
    Route::get('/meetings/listCompanyByUser','MeetingController@listCompanyByUser')->name('meetings.listCompanyByUser');
    Route::get('/meetings/exportCompanyList','MeetingController@exportCompanyList')->name('meetings.exportCompanyList');
    Route::get('/meetings/listProjectByUser','MeetingController@listProjectByUser')->name('meetings.listProjectByUser');
    Route::get('/meetings/exportProjectList','MeetingController@exportProjectList')->name('meetings.exportProjectList');

    Route::get('/meetings/listUpcomingMeetingByUser','MeetingController@listUpcomingMeetingByUser')->name('meetings.listUpcomingMeetingByUser');
    Route::get('/meetings/exportUpcomingMeetingList','MeetingController@exportUpcomingMeetingList')->name('meetings.exportUpcomingMeetingList');
    Route::get('/meetings/listCompletedMeetingByUser','MeetingController@listCompletedMeetingByUser')->name('meetings.listCompletedMeetingByUser');
    Route::get('/meetings/exportCompletedMeetingList','MeetingController@exportCompletedMeetingList')->name('meetings.exportCompletedMeetingList');

    Route::get('/surveys/report', 'SurveyController@report')->name('surveys.report');
    Route::get('/surveys/getProjectStaffs','SurveyController@getProjectStaffs')->name('surveys.getProjectStaffs');
    Route::get('/surveys/getProjectReport','SurveyController@getProjectReport')->name('surveys.getProjectReport');
 
    Route::get('/surveys/report', 'SurveyController@report')->name('surveys.report');
    Route::get('/surveys', 'SurveyController@index')->name('surveys')->middleware('can:viewList,App\Survey');
    Route::get('/surveys/search', 'SurveyController@search')->name('surveys.search')->middleware('can:viewList,App\Survey');
    Route::get('/surveys/index', 'SurveyController@index')->name('surveys.index')->middleware('can:viewList,App\Survey');
    Route::get('/surveys/show/{survey}', 'SurveyController@view')->name('surveys.show')->middleware('can:view,survey');
    Route::get('/surveys/create', 'SurveyController@create')->name('surveys.create')->middleware('can:create,App\Survey');
    Route::post('/surveys/store', 'SurveyController@store')->name('surveys.store')->middleware('can:create,App\Survey');
    Route::get('/surveys/edit/{survey}', 'SurveyController@edit')->name('surveys.edit')->middleware('can:update,survey');
    Route::post('/surveys/update/{survey}', 'SurveyController@update')->name('surveys.update')->middleware('can:update,survey');
    Route::get('/surveys/delete/{survey}', 'SurveyController@delete')->name('surveys.delete')->middleware('can:delete,survey');
    Route::post('/surveys/confirmDelete/{survey}', 'SurveyController@confirmDelete')->name('surveys.confirmDelete')->middleware('can:delete,survey');
    Route::post('/surveys/confirmDeleteAjax/{survey}', 'SurveyController@confirmDeleteAjax')->name('surveys.confirmDeleteAjax')->middleware('can:delete,survey');
    Route::get('/surveys/getProjectUnderDepartment', 'SurveyController@getProjectUnderDepartment')->name('surveys.getProjectUnderDepartment')->middleware('can:create,App\Survey');
    Route::get('/surveys/getContactsUnderCompany', 'SurveyController@getContactsUnderCompany')->name('surveys.getContactsUnderCompany')->middleware('can:create,App\Survey');
    Route::get('/surveys/getContactDetails', 'SurveyController@getContactDetails')->name('surveys.getContactDetails')->middleware('can:create,App\Survey');

    Route::get('/questions', 'QuestionController@index')->name('questions')->middleware('can:viewList,App\Question');
    Route::get('/questions/search', 'QuestionController@search')->name('questions.search')->middleware('can:viewList,App\Question');
    Route::get('/questions/index', 'QuestionController@index')->name('questions.index')->middleware('can:viewList,App\Question');
    Route::get('/questions/show/{question}', 'QuestionController@show')->name('questions.show')->middleware('can:view,App\Question');
    Route::get('/questions/create', 'QuestionController@create')->name('questions.create')->middleware('can:create,App\Question');
    Route::post('/questions/store', 'QuestionController@store')->name('questions.store')->middleware('can:create,App\Question');
    Route::get('/questions/edit/{question}', 'QuestionController@edit')->name('questions.edit')->middleware('can:update,App\Question');
    Route::post('/questions/update/{question}', 'QuestionController@update')->name('questions.update')->middleware('can:update,question');
    Route::get('/questions/delete/{question}', 'QuestionController@delete')->name('questions.delete')->middleware('can:delete,question');
    Route::post('/questions/confirmDelete/{question}', 'QuestionController@confirmDelete')->name('questions.confirmDelete')->middleware('can:delete,question');
    Route::post('/questions/confirmDeleteAjax/{question}', 'QuestionController@confirmDeleteAjax')->name('questions.confirmDeleteAjax')->middleware('can:delete,question');
    Route::get('/questions/getProjectUnderDepartment', 'QuestionController@getProjectUnderDepartment')->name('questions.getProjectUnderDepartment')->middleware('can:viewList,App\Question');
    Route::get('/questions/getContactsUnderCompany', 'QuestionController@getContactsUnderCompany')->name('questions.getContactsUnderCompany')->middleware('can:viewList,App\Question');
    Route::get('/questions/getContactDetails', 'QuestionController@getContactDetails')->name('questions.getContactDetails')->middleware('can:viewList,App\Question');

    Route::get('smsgw/balance','SmsgwController@checkBalance')->name('SMSGW.checkBalance');
    Route::get('smsgw/sendPendingSmses','SmsgwController@sendPendingSMSes')->name('SMSGW.sendPendingSmses');
});

/**
Survey SMS reply Routes
 **/
Route::get('/surveysms/surveySmsReplyReceive', 'MeetingController@surveySmsReplyReceive')->name('surveysms.surveySmsReplyReceive');
Route::get('/sendMail', 'MailController@sendEmail')->name('MailController.sendEmail');
Auth::routes();
