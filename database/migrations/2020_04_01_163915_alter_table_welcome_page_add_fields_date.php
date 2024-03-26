<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableWelcomePageAddFieldsDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('welcome_pages', function (Blueprint $table) {
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
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
            $table->drop('start_date');
            $table->drop('end_date');
        });
    }
}
