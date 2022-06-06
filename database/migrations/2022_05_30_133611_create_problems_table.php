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
        Schema::create('Problems', function (Blueprint $table) {
            $table->increments('problemID');
            $table->string('positions_list')->nullable();
            $table->string('problem_name', 100);
            $table->string('departments_list')->nullable();
            $table->integer('lp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Problems');
    }
};
