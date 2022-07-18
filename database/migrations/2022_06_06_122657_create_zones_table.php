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
        Schema::create('Zones', function (Blueprint $table) {
            $table->increments('zoneID');
            $table->string('zone_name', 100);
        });

        DB::table('Zones')->insert([
            ['zone_name' => 'Production line 1'],
            ['zone_name' => 'Production line 2'],
            ['zone_name' => 'Production line 3'],
            ['zone_name' => 'Production line 4'],
            ['zone_name' => 'Laboratory'],
            ['zone_name' => '3D Measurements'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Zones');
    }
};
