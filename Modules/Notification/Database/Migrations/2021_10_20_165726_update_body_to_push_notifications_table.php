<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBodyToPushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('push_notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('push_notifications', 'body')) {
                $table->json('body')->nullable()->after('stats');
            }
            if (Schema::hasColumn('push_notifications', 'title')) {
                $table->dropColumn('title');
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
            $table->jsonb('body')->nullable();
            $table->string('title');
        });
    }
}
