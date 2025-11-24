<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_software_payments', function (Blueprint $table) {
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->smallInteger('package_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_software_payments', function (Blueprint $table) {
            $table->dropColumn('date_from');
            $table->dropColumn('date_to');
            $table->dropColumn('package_id');
        });
    }
};
