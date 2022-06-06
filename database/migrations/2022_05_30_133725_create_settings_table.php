<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Settings', function (Blueprint $table) {
            $table->increments('settingID');
            $table->string('name', 100)->unique();
            $table->string('value', 100);
            $table->dateTime('date_modified')->nullable();
            $table->string('modified_by')->nullable();
        });

        DB::table('Settings')->insert([
            ['name' => 'dashboard_refreshTime', 'value' => 30],
            ['name' => 'dashboard_newestToDisplay', 'value' => 5],
            ['name' => 'dashboard_chartDaySpan', 'value' => 7],
            ['name' => 'tickets_pagination', 'value' => 20]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Settings');
    }
};
