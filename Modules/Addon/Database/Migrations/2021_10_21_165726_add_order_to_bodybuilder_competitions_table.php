<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderToBodybuilderCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bodybuilder_competitions', function (Blueprint $table) {
            $table->integer('order')->default(0)->nullable()->after('bodybuilder_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bodybuilder_competitions', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
