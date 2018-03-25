<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();
        \DB::table('users')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Laboa',
                'email' => 'casadipaglia@hotmail.com',
                'api_token' => '7bde5a60e1cb124f7c02bc6a12bcfb0f33945a63ee62509b4f48dcbe4f23cae8',
                'password' => bcrypt('Patrick'),
                'active' => 1,
                'telegram_chat_id' => '572616982',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Gabriele',
                'email' => 'soldati.gabriele@gmail.com',
                'api_token' => '7bde5a60e1cb124f7c02bc6a12bcfb0f33945a63ee62509b4f48dcbe4f23cae9',
                'password' => bcrypt('191191'),
                'active' => 1,
                'telegram_chat_id' => '31019486',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => NULL,
            ),
        ));
    }
}