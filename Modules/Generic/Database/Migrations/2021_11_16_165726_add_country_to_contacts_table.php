<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('country')->nullable()->after('phone');
            $table->string('type')->default(0)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('type');
        });
    }
}
