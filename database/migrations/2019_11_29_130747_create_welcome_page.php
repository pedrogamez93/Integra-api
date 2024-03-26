<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelcomePage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welcome_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('icon');
            $table->string('slug');
            $table->string('class');
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
        Schema::dropIfExists('welcome_pages');
    }
}
