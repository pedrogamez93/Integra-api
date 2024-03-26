<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkReleases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_releases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('link_id');
            $table->unsignedBigInteger('release_id');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');;
            $table->foreign('release_id')->references('id')->on('releases')->onDelete('cascade');;
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
        Schema::dropIfExists('link_releases');
    }
}
