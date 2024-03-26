<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_benefits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('link_id');
            $table->unsignedBigInteger('benefit_id');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
            $table->foreign('benefit_id')->references('id')->on('benefits')->onDelete('cascade');
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
        Schema::dropIfExists('link_benefits');
    }
}
