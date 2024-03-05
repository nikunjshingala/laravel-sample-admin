<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->text('allow_all')->nullable();
            $table->text('allow_view')->nullable();
            $table->text('allow_add')->nullable();
            $table->text('allow_edit')->nullable();
            $table->text('allow_delete')->nullable();
            $table->text('allow_search')->nullable();
            $table->text('allow_email')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->string('v_ip','20')->nullable();
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
        Schema::dropIfExists('user_permission');
    }
}
