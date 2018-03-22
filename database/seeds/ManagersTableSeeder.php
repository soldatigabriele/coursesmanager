<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ManagersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('managers')->delete();
        \DB::table('managers')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'laboa',
                'api_token' => '7bde5a60e1cb124f7c02bc6a12bcfb0f33945a63ee62509b4f48dcbe4f23cae8',
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => NULL,
            ),
        ));
    }
}