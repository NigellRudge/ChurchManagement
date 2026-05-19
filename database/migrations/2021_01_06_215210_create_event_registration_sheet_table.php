<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRegistrationSheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_registration_sheet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable()->default(null);
            $table->string('name',50);
            $table->decimal('registration_price');
            $table->unsignedBigInteger('currency_id')->nullable()->default(null);
            $table->date('date')->nullable()->default(null);
            $table->date('last_registration_date');
            $table->boolean('limit_registrations')->default(false);
            $table->integer('max_registrations')->nullable()->default(null);
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
        Schema::dropIfExists('event_registration_sheet');
    }
}
