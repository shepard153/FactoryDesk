<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Staff', function (Blueprint $table) {
            $table->increments('staffID');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('login')->unique();
            $table->string('password');
            $table->string('department');
            $table->rememberToken();
            $table->integer('isAdmin');
            $table->timestamps();
        });

        DB::table('Staff')->insert([
            'name' => 'root',
            'email' => 'root@example.com',
            'login' => 'root',
            'password' => bcrypt('root'),
            'department' => 'All',
            'isAdmin' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Staff');
    }
}
