<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Meeting extends Model {
    protected $table = 'meetings';

    protected $fillable = ['title', 'remarks', 'location', 'project_id', 'client_company_id', 'client_company_contact_person_id', 'start_time', 'end_time', 'outlook_event_id'];

    public function project() {
        return $this->belongsTo('App\Project');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function clientCompany() {
        return $this->belongsTo('App\ClientCompany', 'client_company_id', 'id');
    }

    public function clientCompanyContactPersons() {
        return $this->belongsToMany('App\ClientCompanyContactPerson', 'client_company_contact_people_meeting', 'meeting_id','client_company_contact_person_id');
    }

    public function staffs() {
        return $this->belongsToMany('App\User', 'meeting_user', 'meeting_id','user_id');
    }

    public function survey() {
        return $this->belongsTo('App\Survey');
    }

    public function meetingSurveryResults() {
        return $this->hasMany('App\MeetingSurveryResult');
    }
}
