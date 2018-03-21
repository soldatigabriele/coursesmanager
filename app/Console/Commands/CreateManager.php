<?php

namespace App\Console\Commands;

use App\Manager;
use Illuminate\Console\Command;

class CreateManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CreateManager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create credentials for a new manager';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // ask the user name
        $name = $this->ask('What is the user name?');
        $this->info($name);
        $is_active = $this->choice('Is the manager active? ',['no','yes']);
        $this->info($is_active);
        $is_active = ($is_active == 'yes') ? true : false;

        //generates the apikey
        $token = strtolower(md5(uniqid())).strtolower(md5(uniqid()));


        $this->info('This is his API token: '.$token);

        $manager = new Manager;
        $manager->name = $name;
        $manager->api_token = $token;
        $manager->active = $is_active;
        
        $manager->save();

    }
}
