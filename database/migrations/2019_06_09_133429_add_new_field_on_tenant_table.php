<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldOnTenantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('markers', function (Blueprint $table) {
            $table->float("heading", 6, 3)->nullable();
            $table->integer("calibrate_x")->nullable();
            $table->integer("calibrate_y")->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('markers', function (Blueprint $table) {
            $table->dropColumn('heading');
            $table->dropColumn('calibrate_y');
            $table->dropColumn('calibrate_x');
        });

    }
}
