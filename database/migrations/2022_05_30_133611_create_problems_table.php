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
            $table->text('positions_list')->nullable();
            $table->string('problem_name', 100);
            $table->string('departments_list')->nullable();
            $table->integer('lp')->nullable();
        });

        DB::table('Problems')->insert([
            /**
             * Example IT problems
             */
            [
                'problem_name' => 'PC/Desktop',
                'positions_list' => 'Assembly line, Testing station, Packing station, Office worker, Boiler tests, Microscopes',
                'departments_list' => 'IT',
                'lp' => 1
            ],
            [
                'problem_name' => 'Display',
                'positions_list' => 'Assembly line, Testing station, Packing station, Office worker, Boiler tests, Microscopes',
                'departments_list' => 'IT',
                'lp' => 2
            ],
            [
                'problem_name' => 'Accessories',
                'positions_list' => 'Assembly line, Testing station, Packing station, Office worker, Boiler tests, Microscopes',
                'departments_list' => 'IT',
                'lp' => 3
            ],
            ['problem_name' => 'Barcode scaner', 'positions_list' => 'Packing station', 'departments_list' => 'IT', 'lp' => 4],
            ['problem_name' => 'Label printer','positions_list' => 'Packing station', 'departments_list' => 'IT', 'lp' => 5],
            ['problem_name' => 'Office printer', 'positions_list' => 'Office worker', 'departments_list' => 'IT', 'lp' => 6],
            ['problem_name' => 'AutoCAD', 'positions_list' => 'Office worker, Microscopes', 'departments_list' => 'IT', 'lp' => 7],
            ['problem_name' => 'Office 365', 'positions_list' => 'Office worker', 'departments_list' => 'IT', 'lp' => 8],
            /**
             * Example Quality problems
             */
            ['problem_name' => 'Damaged component', 'positions_list' => 'Assembly line, Testing station', 'departments_list' => 'Quality', 'lp' => 1],
            ['problem_name' => 'Invalid size of component', 'positions_list' => 'Office worker, Microscopes', 'departments_list' => 'Quality', 'lp' => 2],
            ['problem_name' => 'Boilers motherboard', 'positions_list' => 'Testing station, Boiler tests', 'departments_list' => 'Quality', 'lp' => 3],
            ['problem_name' => 'Transponder', 'positions_list' => 'Testing station, Boiler tests', 'departments_list' => 'Quality', 'lp' => 4],
            /**
             * Example Maintenance problems
             */
            [
                'problem_name' => 'Light/Power',
                'positions_list' => 'Assembly line, Testing station, Packing station, Office worker, Boiler tests, Microscopes',
                'departments_list' => 'Maintenance',
                'lp' => 1
            ],
            ['problem_name' => 'Line malfunction', 'positions_list' => 'Assembly line', 'departments_list' => 'Maintenance', 'lp' => 2],
            ['problem_name' => 'Crane malfunction', 'positions_list' => 'Packing station', 'departments_list' => 'Maintenance', 'lp' => 3],
            /**
             * Example Warehouse problems
             */
            ['problem_name' => 'Wrong component', 'positions_list' => 'Assembly line, Testing station, Packing station', 'departments_list' => 'Warehouse', 'lp' => 1],
            ['problem_name' => 'Lack of component', 'positions_list' => 'Assembly line, Testing station, Packing station', 'departments_list' => 'Warehouse', 'lp' => 2],
            ['problem_name' => 'Shipment of goods', 'positions_list' => 'Office worker, Boiler tests, Microscopes', 'departments_list' => 'Warehouse', 'lp' => 3],
        ]);
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
