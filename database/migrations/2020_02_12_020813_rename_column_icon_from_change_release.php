<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnIconFromChangeRelease extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('releases', function (Blueprint $table) {
            $table->string('icon')->nullable()->charset(null)->collation(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('releases', function (Blueprint $table) {
            $table->drop('icon');
        });
    }
}
