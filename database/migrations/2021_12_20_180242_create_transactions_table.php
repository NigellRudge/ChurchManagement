<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('transaction_date');
//            $table->integer('debit');
//            $table->integer('credit');
            $table->integer('amount');
            $table->foreignId('account_id')->constrained('sub_accounts')->cascadeOnDelete();
            $table->string('description');
            $table->string('attachment')->nullable()->default(null);
            $table->foreignId('created_by')->constrained('users');
            $table->unsignedBigInteger('seed_id')->nullable()->default(null);
            $table->unsignedBigInteger('offering_id')->nullable()->default(null);
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
        Schema::dropIfExists('transactions');
    }
}
