<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUserAddRutSurveyUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->unsignedBigInteger('user_rut_list_id')->nullable();
            $table->foreign('user_rut_list_id')->references('id')->on('user_rut_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->drop('user_rut_list_id');
        });
    }
}