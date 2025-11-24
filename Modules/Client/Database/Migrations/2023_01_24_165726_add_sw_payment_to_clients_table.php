<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSwPaymentToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->jsonb('sw_payments')->nullable()->after('sw_url');
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
            $table->dropColumn('sw_payments');
        });
    }
}
