<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsMobileToBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->boolean('is_mobile')->default(false)->nullable()->after('category_id');
            $table->boolean('is_web')->default(false)->nullable()->after('category_id');

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
            $table->dropColumn('is_mobile');
            $table->dropColumn('is_web');
        });
    }
}
