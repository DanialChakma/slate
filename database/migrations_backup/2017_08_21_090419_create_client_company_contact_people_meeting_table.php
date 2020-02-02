<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientCompanyContactPeopleMeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_company_contact_people_meeting', function (Blueprint $table) {
            $table->integer('client_company_contact_person_id')->unsigned();
            $table->foreign('client_company_contact_person_id','contact_people_meeting_contact_person_id_foreign')->references('id')->on('client_company_contact_people');
            $table->integer('meeting_id')->unsigned();
            $table->foreign('meeting_id')->references('id')->on('meetings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_company_contact_people_meeting');
    }
}
