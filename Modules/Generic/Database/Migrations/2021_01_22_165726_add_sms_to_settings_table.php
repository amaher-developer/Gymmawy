<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSmsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('sms_sender_id')->nullable();
            $table->string('sms_username')->nullable();
            $table->string('sms_password')->nullable();

            $table->boolean('sms_new_member')->default(true);
            $table->boolean('sms_renew_member')->default(true);
            $table->boolean('sms_before_expire_member')->default(true);
            $table->boolean('sms_expire_member')->default(true);

            $table->jsonb('sms_new_member_message')->nullable();
            $table->jsonb('sms_renew_member_message')->nullable();
            $table->jsonb('sms_before_expire_member_message')->nullable();
            $table->jsonb('sms_expire_member_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->dropColumn('sms_sender_id');
            $table->dropColumn('sms_username');
            $table->dropColumn('sms_password');

            $table->dropColumn('sms_new_member');
            $table->dropColumn('sms_renew_member');
            $table->dropColumn('sms_before_expire_member');
            $table->dropColumn('sms_expire_member');

            $table->dropColumn('sms_new_member_message');
            $table->dropColumn('sms_renew_member_message');
            $table->dropColumn('sms_before_expire_member_message');
            $table->dropColumn('sms_expire_member_message');
        });
    }
}
