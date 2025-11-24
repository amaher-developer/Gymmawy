<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('country')->nullable();
            $table->string('phone');
            $table->smallInteger('status')->nullable()->default(0);
            $table->string('code')->nullable();
            $table->jsonb('questions')->nullable();
            $table->string('lang', 10)->nullable()->default('ar');
            $table->unsignedInteger('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')
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
        Schema::dropIfExists('training_clients');
    }
}
