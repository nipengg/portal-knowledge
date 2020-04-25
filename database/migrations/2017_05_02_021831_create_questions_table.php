<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question_title');
            $table->string('summary_question');
            $table->integer('user_id')->unsigned();
            $table->integer('user_request_id')->unsigned();
            $table->string('category_name');
            $table->datetime('meeting')->nullable();
            $table->datetime('estimated_time');
            $table->datetime('estimated_time_updated');
            $table->integer('theme_id');
            $table->integer('post_rating')->default(0);
            $table->enum('security',['konfidensial', 'sharing'])->default('sharing');
//            $table->integer('first_post_id')->unsigned();
            $table->integer('accepted_answer_id')->unsigned();
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
        Schema::dropIfExists('questions');
    }
}
