<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserIdToPushTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('push_tokens', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->change();
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
            $table->unsignedInteger('user_id')->nullable(false)->change();
        });
    }
}
