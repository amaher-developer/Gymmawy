<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeNotificationSubscriptionItemToGymSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_subscriptions', function (Blueprint $table) {
            $table->string('lang', 10)->nullable()->default('ar');
            $table->boolean('notify_before_expire_member')->default(0);
            $table->boolean('notify_expire_member')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gym_subscriptions', function (Blueprint $table) {
            $table->dropColumn('lang');
            $table->dropColumn('notify_before_expire_member');
            $table->dropColumn('notify_expire_member');
        });
    }
}
