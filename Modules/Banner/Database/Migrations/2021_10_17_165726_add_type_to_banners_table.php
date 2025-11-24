<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            // gym or trainer
            $table->tinyInteger('type')->nullable()->after('category_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
