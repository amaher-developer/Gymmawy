<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientSMSLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_sms_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('client_id')->nullable()->index();
            $table->foreign('client_id')->references('id')
                ->on('clients')
                ->onDelete('cascade');
            $table->string('content');
            $table->text('phones');
            $table->string('status')->nullable()->default(1);
            $table->jsonb('response')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_sms_logs');
    }
}
