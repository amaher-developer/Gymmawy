<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsBacklinkToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('is_backlinks')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('is_backlinks');
        });
    }
}
