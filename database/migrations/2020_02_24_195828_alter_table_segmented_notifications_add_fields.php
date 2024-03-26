<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSegmentedNotificationsAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('segmented_notifications', function (Blueprint $table) {
            $table->string('code_dependence')->nullable();
            $table->string('code_region')->nullable();
            $table->string('code_position')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segmented_notifications', function (Blueprint $table) {
            $table->drop('code_dependence');
            $table->drop('code_region');
            $table->drop('code_position');
        });
    }
}
