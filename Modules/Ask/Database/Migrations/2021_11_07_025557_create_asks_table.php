<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ask_questions', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedInteger('category_id')->index()->nullable();
            $table->foreign('category_id')->references('id')
                ->on('article_categories')
                ->onDelete('cascade');

            $table->string('question', 300);
            $table->text('details')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->integer('views')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('ask_answers', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedInteger('question_id')->index();
            $table->foreign('question_id')->references('id')
                ->on('ask_questions')
                ->onDelete('cascade');

            $table->unsignedInteger('parent_id')->index()->nullable();
            $table->foreign('parent_id')->references('id')
                ->on('ask_answers')
                ->onDelete('cascade');


            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('answer');
            $table->boolean('published')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('ask_question_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id')->index();
            $table->foreign('question_id')->references('id')
                ->on('ask_questions')
                ->onDelete('cascade');

            $table->unsignedInteger('tag_id')->index();
            $table->foreign('tag_id')->references('id')
                ->on('tags')
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
        Schema::dropIfExists('ask_questions');
        Schema::dropIfExists('ask_answers');
        Schema::dropIfExists('ask_question_tag');
    }
}
