<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->string('col1Name');
            $table->string('col2Name');
            $table->string('col3Name');
            $table->string('col4Name')->nullable();
            $table->boolean('active')->default(true);
            $table->nullableTimestamps();
            $table->softDeletes();
        });

        Schema::create('usersProjects', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->primary(['user_id', 'project_id']);
            $table->boolean('invite_accepted')->default(false);
            $table->boolean('active')->default(true);
            $table->nullableTimestamps();

        });

        Schema::table('tasks', function ($table) {
            $table->foreign('project')->references('id')->on('projects');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('projects');
        Schema::drop('usersProjects');

    }
}
