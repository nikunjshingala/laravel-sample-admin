<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('controller_name', 50);
            $table->string('module_name', 50);
            $table->integer('module_order')->nullable();
            $table->tinyInteger('main_menu')->nullable();
            $table->enum('status',['active','inactive','deleted'])->default('active');
            $table->enum('e_type', ['Secure', 'Unsecure'])->default('Secure');
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
        Schema::dropIfExists('menu_modules');
    }
}
