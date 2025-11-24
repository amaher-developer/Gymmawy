<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGymPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_software_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->nullable();
            $table->string('title');
            $table->text('content')->nullable();
            $table->jsonb('response')->nullable();
            $table->float('price')->default(0);
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
        Schema::dropIfExists('gym_software_payments');
    }
}
