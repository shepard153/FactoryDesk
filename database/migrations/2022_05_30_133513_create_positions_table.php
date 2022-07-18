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
        Schema::create('Positions', function (Blueprint $table) {
            $table->increments('positionID');
            $table->string('position_name', 100);
            $table->text('zones_list');
        });

        DB::table('Positions')->insert([
            ['position_name' => 'Assembly line', 'zones_list' => 'Production line 1, Production line 2, Production line 3, Production line 4'],
            ['position_name' => 'Testing station', 'zones_list' => 'Production line 1, Production line 2, Production line 3, Production line 4'],
            ['position_name' => 'Packing station', 'zones_list' => 'Production line 1, Production line 2, Production line 3, Production line 4'],
            ['position_name' => 'Office worker', 'zones_list' => 'Laboratory, 3D Measurements'],
            ['position_name' => 'Boiler tests', 'zones_list' => 'Laboratory'],
            ['position_name' => 'Microscopes', 'zones_list' => '3D Measurements'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Positions');
    }
};
