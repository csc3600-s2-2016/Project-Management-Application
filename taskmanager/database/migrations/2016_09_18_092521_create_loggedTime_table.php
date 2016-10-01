<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoggedTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loggedTime', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task')->unsigned();
            $table->foreign('task')->references('id')->on('tasks')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->dateTime('start_date_time');
            $table->smallInteger('time_logged');        //change to type decimal (4,2) in next migration
            $table->text('notes');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('loggedTime');
    }
}
