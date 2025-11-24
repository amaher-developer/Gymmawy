<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('gym_id')->index();
            $table->foreign('gym_id')->references('id')
                ->on('gyms')
                ->onDelete('cascade');
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('qr_code')->nullable();
            $table->string('price')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->float('quantity')->nullable();
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
        Schema::dropIfExists('stores');
    }
}
