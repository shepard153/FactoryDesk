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
        Schema::create('Tickets', function (Blueprint $table) {
            $table->increments('ticketID');
            $table->string('device_name', 100);
            $table->string('department', 100);
            $table->string('zone', 150);
            $table->string('position', 150);
            $table->string('problem', 255);
            $table->string('ticket_message', 500)->nullable();
            $table->integer('priority')->nullable();
            $table->dateTime('date_created');
            $table->dateTime('date_modified')->nullable();
            $table->dateTime('date_opened')->nullable();
            $table->dateTime('date_closed')->nullable();
            $table->integer('ticket_status');
            $table->string('owner', 255)->nullable();
            $table->string('username', 255)->nullable();
            $table->time('time_spent')->nullable();
            $table->string('external_ticketID', 255)->nullable();
            $table->string('department_ticketID', 255)->nullable();
            $table->string('target_department', 255)->nullable();
            $table->string('ticket_type', 50)->nullable();
            $table->string('closing_notes', 250)->nullable();
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
        Schema::dropIfExists('Tickets');
    }
};
