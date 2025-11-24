<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLatAndLngToGymsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->string('lat')->nullable()->change();
            $table->string('lng')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->float('lat')->nullable()->change();
            $table->float('lng')->nullable()->change();

        });
    }
}
