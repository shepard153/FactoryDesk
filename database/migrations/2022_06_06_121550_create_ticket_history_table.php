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
        Schema::create('Ticket_history', function (Blueprint $table) {
            $table->increments('editID');
            $table->integer('ticketID');
            $table->string('username', 255);
            $table->string('contents', 255);
            $table->dateTime('date_modified')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Ticket_history');
    }
};
