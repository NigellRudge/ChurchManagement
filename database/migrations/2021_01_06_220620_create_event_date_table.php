<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('event_date', function (Blueprint $table) {
//            $table->id();
//            $table->unsignedBigInteger('event_id');
//            $table->date('date');
//            $table->time('start_time');
//            $table->string('location',60);
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_date');
    }
}
