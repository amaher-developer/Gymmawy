<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatabaseBackupToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('db_backup_name')->nullable();
            $table->dateTime('db_backup_date')->default(\Carbon\Carbon::now());
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

            $table->dropColumn('db_backup_name');
            $table->dropColumn('db_backup_date');
        });
    }
}
