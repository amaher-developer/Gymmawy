<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_id')->index()->nullable();
            $table->string('google_id')->index()->nullable();
            $table->string('twitter_id')->index()->nullable();
            $table->string('instagram_id')->index()->nullable();
            $table->boolean('block')->default(false)->index();
            $table->boolean('featured')->default(false)->index();
            $table->boolean('activated')->default(false)->index();
            $table->string('image')->nullable();
            $table->text('about')->nullable();
            $table->integer('articles')->default(0);
            $table->integer('views')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
