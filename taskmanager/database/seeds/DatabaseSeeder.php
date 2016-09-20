<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 6)->create();

        factory(App\Task::class, 3)->create()->each(function($t) {
	        $t->subtasks()->saveMany(factory(App\Subtask::class, 3)->make());
	    //     $t->subtasks()->saveMany(factory(App\LoggedTime::class, 6)->make());
	    });
    }
}
