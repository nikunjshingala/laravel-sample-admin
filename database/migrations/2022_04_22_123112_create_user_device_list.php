<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDeviceList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_device_list', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->string('api_level',255)->nullable();
            $table->string('brand',255)->nullable();
            $table->string('build_number',255)->nullable();
            $table->string('device_country',255)->nullable();
            $table->string('device_name',255)->nullable();
            $table->string('manufacturer',255)->nullable();
            $table->string('model',255)->nullable();
            $table->string('system_name',255)->nullable();
            $table->string('system_version',255)->nullable();
            $table->string('version',255)->nullable();
            $table->string('device_token',255)->nullable();
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
        Schema::dropIfExists('user_device_list');
    }
}
