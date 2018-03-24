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
        $this->call(UsersTableSeeder::class);
        factory('App\Course', 10)->create(['user_id' => 1])->each(function($u){
            $u->partecipants()->saveMany(factory('App\Partecipant', 10)->create());
        });
    }
}
