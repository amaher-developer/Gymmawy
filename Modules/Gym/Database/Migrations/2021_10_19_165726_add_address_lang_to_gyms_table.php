<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressLangToGymsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->string('address_ar')->nullable()->after('address');
            $table->string('address_en')->nullable()->after('address');

        });
        DB::statement('UPDATE gyms SET address_ar = address, address_en = address');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->dropColumn('address_ar');
            $table->dropColumn('address_en');
        });
    }
}
