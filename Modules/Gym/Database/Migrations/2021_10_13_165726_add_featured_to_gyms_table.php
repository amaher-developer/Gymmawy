<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeaturedToGymsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->boolean('featured')->nullable()->after('published')->default(false);

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
            $table->dropColumn('featured');
        });
    }
}
