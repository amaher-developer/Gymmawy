<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStatsInPushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('push_notifications', function (Blueprint $table) {
            $table->text('stats')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('push_notifications', function (Blueprint $table) {
            $table->text('stats')->nullable(false)->change();
        });
    }
}
