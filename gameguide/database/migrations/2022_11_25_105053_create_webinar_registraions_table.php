<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebinarRegistraionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar_registraions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('user_full_name');
            $table->string('user_email');
            $table->integer('webinar_id');
            $table->string('registraion_date');
            $table->string('registraion_time');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webinar_registraions');
    }
}
