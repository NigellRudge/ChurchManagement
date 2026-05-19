<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offerings', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->date('date');
            $table->string('counted_by');
            $table->unsignedBigInteger('event_id')->nullable()->default(null);
            $table->integer('srd_amount');
            $table->integer('usd_amount');
            $table->integer('euro_amount');
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
        Schema::dropIfExists('offerings');
    }
}
