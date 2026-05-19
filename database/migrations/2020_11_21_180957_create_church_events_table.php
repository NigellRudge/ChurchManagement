<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChurchEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('church_events', function (Blueprint $table) {
            $table->id();
            $table->string('title',60);
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->text('description')->nullable()->default(null);
            $table->boolean('should_register')->default(false);
            $table->date('last_registration_date')->nullable()->default(null);
            $table->date('last_payment_date')->nullable()->default(null);
            $table->boolean('is_paid_event')->default(false);
           // $table->unsignedBigInteger('currency_id')->nullable()->default(null);
            $table->decimal('price')->nullable()->default(null);
            $table->decimal('registration_price')->nullable()->default(null);
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
        Schema::dropIfExists('church_events');
    }
}
