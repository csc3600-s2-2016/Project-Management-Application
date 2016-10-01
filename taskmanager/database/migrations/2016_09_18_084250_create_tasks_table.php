<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->smallInteger('status')->unsigned();
            $table->integer('priority')->unsigned();
            $table->integer('time_estimated')->unsigned()->nullable();
            $table->date('due_date')->nullable();
            $table->integer('created_by')->unsigned();
            $table->nullableTimestamps();

            //On next migration
            //add columns: archived, project
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks');
    }
}
