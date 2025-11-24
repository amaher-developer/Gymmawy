<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteBodyToPushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('push_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('push_notifications', 'body')) {
                $table->dropColumn('body');
            }
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
            $table->string('body')->nullable();
        });
    }
}
