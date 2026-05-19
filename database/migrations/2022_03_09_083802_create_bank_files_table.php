<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_name');
            $table->dateTime('upload_date');
            $table->enum('status',[config('constants.BANK_FILE_STATUS_PENDING'), config('constants.BANK_FILE_STATUS_MATCHING'),config('constants.BANK_FILE_STATUS_MATCHED')]);
            $table->foreignId('bank_file_type_id')->constrained('bank_file_types','id')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users','id');
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
        Schema::dropIfExists('bank_files');
    }
}
