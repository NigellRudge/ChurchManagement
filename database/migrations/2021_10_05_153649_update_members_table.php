<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members',function (Blueprint $table){
            $table->unsignedBigInteger('education_id')->nullable()->default(1);
            $table->string('job_description',50)->nullable()->default(null);
            $table->string('maiden_name',50)->nullable()->default(null);
            $table->text('skills')->nullable()->default(null);
            $table->string('neighborhood')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
            $table->date('marriage_date')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members',function (Blueprint $table){
            $table->dropColumn('education_id');
            $table->dropColumn('job_description');
            $table->dropColumn('skills');
            $table->dropColumn('neighborhood');
        });
    }
}
