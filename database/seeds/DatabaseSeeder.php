<?php

namespace Database\Seeders;

use UsersTableSeeder;
use RegionsTableSeeder;
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
        $this->call(RegionsTableSeeder::class);
        if (env('APP_ENV') !== 'production') {
            factory('App\Course', random_int(4, 20))->create()->each(function ($u) {
                $u->partecipants()->saveMany(factory('App\Partecipant', random_int(0, 10))->create());
            });
            factory('App\Newsletter', 40)->create();
        }
    }
}
