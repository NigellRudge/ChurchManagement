<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRegistrationItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_sheet_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sheet_id')->constrained('event_registration_sheet');
            $table->foreignId('member_id')->constrained('members');
            $table->date('registration_date');
            $table->unsignedBigInteger('currency_id')->nullable()->default(null);
            $table->decimal('paid_amount')->nullable()->default(null);
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
        Schema::dropIfExists('event_registration_item');
    }
}
