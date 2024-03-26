<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegmentedNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('segmented_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position_code')->nullable();
            $table->string('dependence_code')->nullable();
            $table->integer('region_code')->nullable();
            $table->integer('object_id')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->string('type')->nullable();
            $table->text('text')->nullable();
            $table->text('headings')->nullable();
            $table->tinyInteger('is_send_notification')->default(0);
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
        Schema::dropIfExists('segmented_notifications');
    }
}
