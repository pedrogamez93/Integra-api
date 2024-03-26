<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableRenameFieldsVideoNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('onboardings', function (Blueprint $table) {
            $table->string('video')->nullable()->charset(null)->collation(null)->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('onboardings', function (Blueprint $table) {
            $table->delete('video');
        });
    }
}
