<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('token')->nullable();
            $table->unsignedInteger('gym_id')->nullable()->index();
            $table->foreign('gym_id')->references('id')
                ->on('gyms')
                ->onDelete('cascade');
            $table->string('sms_balance')->nullable()->default(0);
            $table->string('sms_sender_id')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
