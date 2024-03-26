<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('rut')->nullable()->unique();
            $table->string('dv')->nullable();
            $table->string('werks')->nullable();
            $table->string('address')->nullable();
            $table->string('persk')->nullable();
            $table->string('text20')->nullable();
            $table->string('position')->nullable();
            $table->string('tipest')->nullable();
            $table->string('phone')->nullable();
            $table->string('politics')->nullable();
            $table->string('email')->nullable();
            $table->string('personal_mail')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('status')->nullable();
            $table->tinyInteger('is_termn_service')->nullable();
            $table->tinyInteger('is_termn_home')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
