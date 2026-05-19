<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address')->nullable();
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->date('birth_date');
            $table->date('join_date')->nullable();
            $table->date('end_date')->nullable()->default(null);
            $table->date('convert_date')->nullable();
            $table->boolean('married')->nullable()->default(false);
            $table->boolean('active')->nullable()->default(true);
            $table->boolean('candidate_member')->default(true);
            $table->date('member_type_change_date')->nullable()->default(null);
            $table->string('image',150)->nullable();
            $table->string('id_number',12)->nullable()->default(null);
            $table->softDeletes();
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
        Schema::dropIfExists('members');
    }
}
