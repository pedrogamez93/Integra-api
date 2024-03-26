<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSendNotificateReleases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('releases', function (Blueprint $table) {
            $table->tinyInteger('is_send_notification')->default(0);
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
            $table->drop('is_send_notification');
        });
    }
}
