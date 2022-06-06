<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Departments', function (Blueprint $table) {
            $table->increments('departmentID');
            $table->string('department_name', 255);
            $table->string('image_path')->nullable();
            $table->string('department_prefix', 50)->nullable();
            $table->boolean('isHidden');
            $table->string('acceptance_from', 255)->nullable();
            $table->string('teams_webhook')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Departments');
    }
};
