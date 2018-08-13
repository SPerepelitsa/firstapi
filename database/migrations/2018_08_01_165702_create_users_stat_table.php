<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersStatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_stat', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->ipAddress('ip');
            $table->string('country_code', 3)->nullable();
            $table->string('region_name')->nullable();
            $table->string('city_name')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version', 50)->nullable();
            $table->string('os')->nullable();
            $table->string('os_version', 50)->nullable();
            $table->text('previous_page')->nullable();
            $table->string('visited_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_stat');
    }
}
