<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableWelcomePageAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('welcome_pages', function (Blueprint $table) {
            $table->text('title_certificate')->nullable();
            $table->text('description_certificate')->nullable();
            $table->text('label_button')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('welcome_pages', function (Blueprint $table) {
            $table->drop('title_certificate');
            $table->drop('description_certificate');
            $table->drop('label_button');
        });
    }
}
