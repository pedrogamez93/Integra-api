<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleaseBenefits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('release_benefits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('release_id');
            $table->unsignedBigInteger('benefits_id');

            $table->foreign('release_id')->references('id')->on('releases')->onDelete('cascade');
            $table->foreign('benefits_id')->references('id')->on('benefits')->onDelete('cascade');

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
        Schema::dropIfExists('release_benefits');
    }
}
