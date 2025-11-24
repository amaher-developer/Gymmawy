<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneCodeToCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->string('phone_code')->nullable()->after('name_ar');
            $table->string('symbol')->nullable()->after('name_ar');
            $table->string('currency')->nullable()->after('name_ar');
            $table->string('code')->nullable()->after('name_ar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('phone_code');
            $table->dropColumn('symbol');
            $table->dropColumn('currency');
        });
    }
}
