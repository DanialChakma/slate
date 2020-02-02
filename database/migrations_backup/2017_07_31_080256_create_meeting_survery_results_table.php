<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingSurveryResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_survery_results', function (Blueprint $table) {
            $table->increments('id');
            $table->string('msisdn')->comment('mobile number');
            $table->integer('survey_id')->unsigned();
            $table->foreign('survey_id')->references('id')->on('surveys');

            $table->integer('meeting_id')->unsigned();
            $table->foreign('meeting_id')->references('id')->on('meetings');

            $table->integer('question_id')->unsigned();
            $table->foreign('question_id')->references('id')->on('questions');

            $table->integer('answer_option_id')->unsigned()->nullable();
            $table->foreign('answer_option_id')->references('id')->on('answer_options');
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
        Schema::dropIfExists('meeting_survery_results');
    }
}
