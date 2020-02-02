<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientCompanyContactPerson extends Model
{


    protected $table = 'client_company_contact_people';

    protected $fillable = [
        'client_company_id',
        'name',
        'designation',
        'phone',
        'email',
        'remarks'
    ];

    public function role(){
        return $this->belongsTo('App\Role');
    }
    public function clientCompany(){
        return $this->belongsTo('App\ClientCompany');
    }
    public function meetings(){
        return $this->belongsToMany('App\Meeting', 'client_company_contact_people_meeting', 'client_company_contact_person_id','meeting_id');
    }
}
