<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenefitDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benefit_districts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('benefit_id');
            $table->unsignedBigInteger('district_id');
            $table->foreign('benefit_id')->references('id')->on('benefits')->onDelete('cascade');
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
        Schema::dropIfExists('benefit_districts');
    }
}
