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
                'password' => bcrypt('Patrick'),
                'active' => 1,
                'telegram_chat_id' => '572616982',
                'telegram_settings' => json_encode(['telegram_chat_id'=>'572616982', 'disable_notification'=>false, 'active' => true]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => NULL,
            ),
        ));
    }
}