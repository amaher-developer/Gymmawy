<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeItemToGymSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->dropColumn('gym_name');
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
            $table->unsignedInteger('user_id')->nullable(false)->change();
            $table->string('gym_name')->nullable();

        });
    }
}
