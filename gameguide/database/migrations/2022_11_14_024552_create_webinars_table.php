<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebinarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('start_datetime');
            $table->string('end_datetime');
            $table->string('logo_image')->nullable();
            $table->string('logo_original_image')->nullable();
            $table->string('logo_mimes')->nullable();
            $table->string('featuredImg_image')->nullable();
            $table->string('featuredImg_original_image')->nullable();
            $table->string('featuredImg_mimes')->nullable();
            $table->integer('status');
            $table->string('coach_user_id')->nullable();
            $table->string('webinar_link')->nullable();
            $table->string('description')->nullable();
            $table->integer('status')->nullable();
            $table->string('streamKey')->nullable();
            $table->string('lang_code')->default('en');

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
        Schema::dropIfExists('webinars');
    }
}