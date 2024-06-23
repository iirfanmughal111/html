<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_guides', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('game_id');
            $table->bigInteger('guide_type_id');
            $table->string('title', 150);
            $table->string('slug', 150);
            $table->text('description')->nullable();
            $table->text('video_notes')->nullable();
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
        Schema::dropIfExists('game_guides');
    }
}
