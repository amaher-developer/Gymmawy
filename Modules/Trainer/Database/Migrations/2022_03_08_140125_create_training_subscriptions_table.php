<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('image')->nullable();
            $table->text('content')->nullable();

            $table->unsignedInteger('training_client_id')->index()->nullable();
            $table->foreign('training_client_id')->references('id')
                ->on('training_clients')
                ->onDelete('cascade');
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
        Schema::dropIfExists('training_subscriptions');
    }
}
