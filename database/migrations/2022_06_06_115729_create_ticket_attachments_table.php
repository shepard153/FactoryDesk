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
        Schema::create('Ticket_attachments', function (Blueprint $table) {
            $table->increments('attachmentID');
            $table->integer('ticketID')->nullable();
            $table->string('file_name', 255)->nullable();
            $table->string('file_path', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Ticket_attachments');
    }
};
