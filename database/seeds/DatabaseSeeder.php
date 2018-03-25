
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
        $this->call(RegionsTableSeeder::class);
        factory('App\Course', random_int(4, 20))->create(['user_id' => 1])->each(function($u){
            $u->partecipants()->saveMany(factory('App\Partecipant', random_int(0, 10))->create());
        });
    }
}
