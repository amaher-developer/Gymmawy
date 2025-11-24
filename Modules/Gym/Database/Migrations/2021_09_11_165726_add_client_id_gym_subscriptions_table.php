<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientIdGymSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gym_subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('client_id')->index();
            $table->foreign('client_id')->references('id')
                ->on('clients')
                ->onDelete('cascade');
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
            $table->dropColumn('client_id');
        });
    }
}
