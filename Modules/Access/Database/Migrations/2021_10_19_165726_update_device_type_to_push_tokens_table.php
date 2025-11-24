<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDeviceTypeToPushTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('push_tokens', function (Blueprint $table) {
            $table->string('device_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('push_tokens', function (Blueprint $table) {
            $table->integer('device_type')->nullable(false)->change();
        });
    }
}
