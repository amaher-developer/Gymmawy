<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateFromToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->timestamp('date_from')->nullable()->useCurrent()->after('sms_sender_id');
            $table->timestamp('date_to')->nullable()->useCurrent()->after('sms_sender_id');
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
            $table->dropColumn('date_from');
            $table->dropColumn('date_to');
        });
    }
}
