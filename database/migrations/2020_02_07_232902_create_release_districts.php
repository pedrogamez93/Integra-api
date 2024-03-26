<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleaseDistricts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('release_districts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('release_id');
            $table->unsignedBigInteger('district_id');
            $table->foreign('release_id')->references('id')->on('releases')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');

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
        Schema::dropIfExists('release_districts');
    }
}
