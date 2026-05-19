<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorsSheetItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors_sheet_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sheet_id')->constrained('visitors_sheet')->cascadeOnDelete();
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->foreignId('invited_by_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('gender_id')->constrained('genders')->cascadeOnDelete();
            $table->string('phone_number')->nullable()->default('no number provided');
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
        Schema::dropIfExists('visitors_sheet_item');
    }
}
