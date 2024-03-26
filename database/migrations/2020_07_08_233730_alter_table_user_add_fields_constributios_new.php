<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUserAddFieldsConstributiosNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('constributions', function (Blueprint $table) {
            $table->string('email')->nulable();
            $table->string('text_check')->nulable();
            $table->text('gratitude')->nulable();
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
            //
        });
    }
}
