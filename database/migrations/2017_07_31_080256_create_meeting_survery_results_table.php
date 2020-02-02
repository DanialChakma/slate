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
            $table->text('user_input')->nullable()->comment('User Input for Open-text Question');

            $table->text('sent_params_to_sgw')->nullable(); //LOGGING
            $table->text('sgw_response')->nullable(); //LOGGING
            $table->bigInteger('sgw_message_id')->nullable();

            $table->text('sgw_delivery_receipts')->nullable(); //LOGGING
            $table->string('sgw_status')->nullable(); //null, PROCESSED, SENT, RECEIVED otherwise FAILED
            $table->boolean('failed_for_no_balance')->default(false)->comment('SMS Sending Failed for No balance FLAG. 0 means false,1 means true.');
            $table->boolean('failed_for_other_reason')->default(false)->comment('SMS Sending Failed for Other reason.');
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
