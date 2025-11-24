<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBodyToPushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('push_notifications', function (Blueprint $table) {
            $table->string('body')->nullable()->after('title');
            $table->unsignedInteger('user_id')->index()->nullable()->after('title');
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
            $table->dropColumn('user_id');
            $table->dropColumn('body');
        });
    }
}
