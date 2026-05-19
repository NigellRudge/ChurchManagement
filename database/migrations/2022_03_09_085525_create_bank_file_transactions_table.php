<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankFileTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_file_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->integer('amount');
            $table->dateTime('transaction_date');
            $table->foreignId('bank_file_id')->constrained('bank_files','id')->cascadeOnDelete();
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
        Schema::dropIfExists('bank_file_transactions');
    }
}
