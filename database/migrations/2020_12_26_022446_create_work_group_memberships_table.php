<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkGroupMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_group_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('work_groups')->cascadeOnDelete();
//            $table->unsignedBigInteger('member_id');
//            $table->unsignedBigInteger('group_id');
            $table->boolean('active')->default(true);
            $table->date('join_date');
            $table->date('exit_date')->nullable()->default(null);
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
        Schema::dropIfExists('work_group_memberships');
    }
}
