<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMimesToGameGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_guides', function (Blueprint $table) {
            $table->string('original_image')->after('image')->nullable();
            $table->string('mimes',50)->after('original_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_guides', function (Blueprint $table) {
            $table->dropColumn('original_image');
            $table->dropColumn('mimes');
        });
    }
}