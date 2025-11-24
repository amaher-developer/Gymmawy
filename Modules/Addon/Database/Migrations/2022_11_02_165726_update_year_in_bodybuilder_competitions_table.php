<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateYearInBodybuilderCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bodybuilder_competitions', function (Blueprint $table) {
            $table->string('year', 12)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bodybuilders', function (Blueprint $table) {
            $table->string('year', 8)->change();
        });
    }
}
