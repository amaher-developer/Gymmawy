<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unsignedInteger('category_id')->index();
            $table->foreign('category_id')->references('id')
                ->on('article_categories')
                ->onDelete('cascade');
            $table->string('language', 5)->default('ar')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->boolean('published')->default(false)->index();
            $table->string('image')->nullable();
            $table->string('youtube')->nullable();
            $table->boolean('status')->default(false)->comment('');
            $table->integer('views')->default(0);
            $table->smallInteger('reject_reason')->default(0);
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
        Schema::dropIfExists('articles');
    }
}
