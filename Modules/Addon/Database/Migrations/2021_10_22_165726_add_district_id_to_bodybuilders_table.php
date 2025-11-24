<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDistrictIdToBodybuildersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bodybuilders', function (Blueprint $table) {
            $table->unsignedInteger('district_id')->after('country_id')->nullable();
            $table->foreign('district_id')->references('id')
                ->on('districts')
                ->onDelete('cascade');

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
            $table->dropColumn('district_id');
        });
    }
}
