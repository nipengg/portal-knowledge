<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('qa_title');
            $table->enum('security',['konfidensial', 'sharing']);
            $table->string('category_name');
            $table->integer('user_id')->unsigned();
            $table->integer('user_request_id')->unsigned();
            $table->integer('accepted_qa_id')->unsigned();
            $table->boolean('is_active');
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
        Schema::dropIfExists('qas');
    }
}
