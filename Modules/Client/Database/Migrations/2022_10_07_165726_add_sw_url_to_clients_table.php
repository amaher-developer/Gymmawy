<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSwUrlToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('sw_url')->nullable()->after('sms_sender_id');
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
            $table->dropColumn('sw_url');
        });
    }
}
