<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientCompanyContactPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_company_contact_people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 190);
			$table->string('designation',190);
            $table->string('email', 100);
            $table->string('phone', 40);
            $table->text('remarks')->nullable();

            $table->integer('client_company_id')->unsigned();
            $table->foreign('client_company_id')->references('id')->on('client_companies');
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
        Schema::dropIfExists('client_company_contact_people');
    }
}
