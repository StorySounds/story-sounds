<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoundTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sound_triggers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('play_on_start')->default(0);
            $table->integer('volume');
            $table->decimal('seconds_fade_out', 10, 2)->default(0.00);
            $table->decimal('seconds_fade_in', 10, 2)->default(0.00);
            //Needs to be added once we add filters table
            //$table->integer('filter_id');
            $table->boolean('looping')->default(0);
            $table->decimal('seconds_offset', 10, 2)->default(0.00);
            $table->string('trigger_phrase')->nullable();
            $table->unsignedBigInteger('play_after_sound_id')->nullable();
            $table->unsignedBigInteger('sound_id');
            $table->timestamps();

            $table->foreign('play_after_sound_id')->references('id')->on('sound_triggers');
            $table->foreign('sound_id')->references('id')->on('sounds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sound_triggers');
    }
}
