<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastMigrateToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('last_migrate', 100)->nullable()->after('sw_url');
            $table->boolean('status')->default(0)->nullable()->after('sw_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('last_migrate');
            $table->dropColumn('status');
        });
    }
}
