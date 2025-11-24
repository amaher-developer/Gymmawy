<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCityIdTrainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->string('city_id')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->string('city_id')->nullable(false)->change();
        });
    }
}
