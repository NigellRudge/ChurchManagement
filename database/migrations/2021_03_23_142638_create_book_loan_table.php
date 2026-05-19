<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_loan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_item_id');
            $table->unsignedBigInteger('library_member_id');
            $table->date('loan_date');
            $table->date('due_date')->nullable()->default(null);
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
        Schema::dropIfExists('book_loan');
    }
}
