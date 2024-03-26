<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableWelcomePageAddFieldsHome extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('welcome_pages', function (Blueprint $table) {
            $table->boolean('type_certificate')->nullable();
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
        });
    }
}
