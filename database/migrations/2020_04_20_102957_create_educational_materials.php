<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationalMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educational_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->string('title')->nullable();
            $table->string('is_activity')->nullable();
            $table->date('date')->nullable();
            $table->text('post_intro')->nullable();
            $table->text('post_content')->nullable();
            $table->json('documents')->nullable();
            $table->dateTime('datetime')->nullable();
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
        Schema::dropIfExists('educational_materials');
    }
}
