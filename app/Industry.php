<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\ClientCompany;
class Industry extends Model
{
    protected $table = 'industries';

    protected $fillable = [
        'name'
    ];

    public function clientCompanies(){
        return $this->hasMany("App\ClientCompany");
    }
}
