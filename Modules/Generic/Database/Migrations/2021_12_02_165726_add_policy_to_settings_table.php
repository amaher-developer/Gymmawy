<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPolicyToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('policy_ar')->nullable()->after('terms_ar');
            $table->text('policy_en')->nullable()->after('terms_ar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('policy_ar');
            $table->dropColumn('policy_en');
        });
    }
}
