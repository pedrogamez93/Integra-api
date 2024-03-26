<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benefits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->string('title');
            $table->date('datetime');
            $table->text('post_intro');
            $table->text('post_content');
            $table->unsignedBigInteger('district_id')->nullable();
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->boolean('is_send_notification')->default(0);
            $table->text('video', 255)->nullable();
            $table->boolean('is_public')->default(0);
            $table->boolean('is_private')->nullable();
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
        Schema::dropIfExists('benefits');
    }
}
