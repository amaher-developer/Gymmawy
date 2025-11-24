<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('gym_id')->nullable()->index();
            $table->foreign('gym_id')->references('id')
                ->on('gyms')
                ->onDelete('cascade');
            $table->unsignedInteger('category_id')->nullable()->index();
            $table->foreign('category_id')->references('id')
                ->on('categories')
                ->onDelete('cascade');
            $table->enum('lang', ['ar', 'en'])->default('ar')->nullable();
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('phone')->nullable();
            $table->string('url')->nullable();
            $table->timestamp('date_from')->nullable();
            $table->timestamp('date_to')->nullable();
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
        Schema::dropIfExists('banners');
    }
}
