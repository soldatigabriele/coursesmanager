<?php

namespace App\Jobs;

use App\Helpers\Logger;
use App\Helpers\Telegram;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TelegramAlert implements ShouldQueue
{
    private $disableNotification;
    private $text;
    private $request;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($text, $disableNotification, $request)
    {
        //
        $this->disableNotification = $disableNotification;
        $this->text = $text;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        $telegram = new Telegram($client);
        $response = $telegram->alert($this->text, $this->disableNotification);
        (new Logger)->log('2', 'Telegram Response', $response, $this->request);
    }
}
