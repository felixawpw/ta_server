<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('point_x');
            $table->integer('point_y');
            $table->integer('marker_type');

            $table->integer('map_id')->unsigned();
            $table->foreign('map_id')->references('id')->on('maps')->onDelete('cascade');

            $table->integer('connecting_marker_id')->unsigned()->nullable();
            $table->foreign('connecting_marker_id')->references('id')->on('markers')->onDelete('cascade');
            
            $table->float("heading", 6, 3)->nullable();
            $table->integer("calibrate_x")->nullable();
            $table->integer("calibrate_y")->nullable();

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
        Schema::dropIfExists('markers');
    }
}
