<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTokenToAsksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ask_questions', function (Blueprint $table) {
            $table->string('token')->nullable()->after('views');
            $table->boolean('published')->default(true)->index()->after('views');
        });
        Schema::table('ask_answers', function (Blueprint $table) {
            $table->string('token')->nullable()->after('published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ask_questions', function (Blueprint $table) {
            $table->dropColumn('token');
            $table->dropColumn('published');
        });
        Schema::table('ask_answers', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
}
