<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientCompany extends Model
{
    protected $table = 'client_companies';

    protected $fillable = [
        'industry_id',
        'company_name',
        'remarks'
    ];

    public function industry(){
        return $this->belongsTo("App\Industry");
    }

    public function clientCompanyContactPersons(){
        return $this->hasMany('App\ClientCompanyContactPerson');
    }

    public function meetings(){
        return $this->hasMany('App\Meeting');
    }
}
