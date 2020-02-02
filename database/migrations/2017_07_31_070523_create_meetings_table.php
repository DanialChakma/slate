<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
			$table->string('location',255)->nullable()->comment('Meeting Location.');
            $table->enum('status', ['Initiated', 'Completed', 'Rescheduled', 'Cancelled'])->default('Initiated');
            $table->string('remarks', 190);

            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');

//            $table->integer('user_id')->unsigned()->comment("field stuff id");
//            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('client_company_id')->unsigned();
            $table->foreign('client_company_id')->references('id')->on('client_companies');

            $table->integer('survey_id')->unsigned()->nullable();
            $table->foreign('survey_id')->references('id')->on('surveys');

            $table->boolean('all_sms_sent')->default(0);
            $table->boolean('survey_duration_over')->default(0);

            $table->dateTimeTz('start_time');
            $table->dateTimeTz('end_time')->nullable();

            $table->text('outlook_event_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
