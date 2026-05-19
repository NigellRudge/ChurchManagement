<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerAttendanceSheetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_attendance_sheet_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sheet_id')->constrained('worker_attendance_sheets')->cascadeOnDelete();
            $table->foreignId('worker_id')->constrained('members')->cascadeOnDelete();
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
        Schema::dropIfExists('worker_attendance_sheet_items');
    }
}
