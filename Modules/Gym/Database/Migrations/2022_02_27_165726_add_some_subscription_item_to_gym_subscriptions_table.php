<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeSubscriptionItemToGymSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_subscriptions', function (Blueprint $table) {
            $table->string('subscription_name_en')->nullable();
            $table->string('subscription_name_ar')->nullable();
            $table->integer('workouts')->default(0);
            $table->integer('visits')->default(0);
            $table->integer('amount_remaining')->default(0);
            $table->timestamp('joining_date')->nullable();
            $table->timestamp('expire_date')->nullable();
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

            $table->dropColumn('subscription_name_ar');
            $table->dropColumn('subscription_name_en');
            $table->dropColumn('workouts');
            $table->dropColumn('visits');
            $table->dropColumn('amount_remaining');
            $table->dropColumn('joining_date');
            $table->dropColumn('expire_date');

        });
    }
}
