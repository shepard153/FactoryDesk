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
            $table->string('discord_webhook')->nullable();
            $table->string('department_email')->nullable();
        });

        DB::table('Departments')->insert([
            ['department_name' => 'IT', 'image_path' => 'img/it.jpg', 'department_prefix' => 'I', 'isHidden' => false],
            ['department_name' => 'Quality', 'image_path' => 'img/quality.jpg', 'department_prefix' => 'Q', 'isHidden' => false],
            ['department_name' => 'Maintenance', 'image_path' => 'img/maintenance.jpg', 'department_prefix' => 'M', 'isHidden' => false],
            ['department_name' => 'Warehouse', 'image_path' => 'img/warehouse.jpg', 'department_prefix' => 'W', 'isHidden' => false],
        ]);
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
