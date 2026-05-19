<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceMemberSectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_member_sectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_member_id')->constrained('service_club_members')->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained('business_sectors')->cascadeOnDelete();
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
        Schema::dropIfExists('service_member_sectors');
    }
}
