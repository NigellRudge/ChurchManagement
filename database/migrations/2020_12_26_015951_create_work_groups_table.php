<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->foreignId('coordinator_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('pastor_id')->constrained('members')->cascadeOnDelete();
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
        Schema::dropIfExists('work_groups');
    }
}
