<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUserAddFieldsConstributios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('constributions', function (Blueprint $table) {
            $table->dateTime('init_date')->nullable();
            $table->dateTime('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('constributions', function (Blueprint $table) {
            $table->drop('init_date');
            $table->drop('end_date');
        });
    }
}
