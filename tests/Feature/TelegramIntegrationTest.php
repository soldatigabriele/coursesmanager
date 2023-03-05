<?php

namespace Tests\Feature;

use Mockery;
use App\Course;
use App\Region;
use Faker\Factory;
use Tests\TestCase;
use App\ApplicationLog;
use App\Helpers\Telegram;
use App\Jobs\TelegramAlert;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TelegramIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory('App\User')->create();

        $this->faker = Factory::create('it_IT');

        $data = [];
        $data['job'] = $this->faker->jobTitle;
        $trans = ['auto', 'treno', 'bici'];
        $data['transport'] = $trans[mt_rand(0, count($trans) - 1)];
        $source = ['facebook', 'sito', 'amici'];
        $data['source'] = $source[mt_rand(0, count($source) - 1)];
        $data['shares'] = random_int(0, 1);
        $data['city'] = $this->faker->city;
        $data['fiscal_code'] = $this->faker->taxId;
        $food = ['veget', 'vegano', 'onnivoro'];
        $data['food'] = $food[mt_rand(0, count($food) - 1)];
        $email = $this->faker->unique()->safeEmail;

        $this->newPartecipantData = [
            'slug' => str_random(20),
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'region_id' => Region::inRandomOrder()->first()->id,
            'email' => $email,
            'email_again' => $email,
            'phone' => '3' . rand(111111111, 999999999),
            'job' => $data['job'],
            'city' => $data['city'],
            'meta' => json_encode(['ip' => '127.0.0.2']),
        ];

        $this->newNewsletterData = [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'region_id' => Region::inRandomOrder()->first()->id,
            'active' => 1,
            'meta' => json_encode(['ip' => '127.0.0.2']),
        ];
        factory('App\Course', 10)->create();
    }

    // public function test_newsletter_subscription_triggers_telegram_alert()
    // {
    //     Queue::fake();
    //     $res = $this->post(route('newsletter.store'), $this->newNewsletterData);
    //     Queue::assertPushed(TelegramAlert::class, 1);
    // }

    public function test_participant_subscription_triggers_telegram_alert()
    {
        Queue::fake();
        $course = Course::inRandomOrder()->first();
        $this->newPartecipantData['course_id'] = $course->id;
        $res = $this->post(route('partecipant.store'), $this->newPartecipantData);
        Queue::assertPushed(TelegramAlert::class, 1);
    }

    // public function test_telegram_message_is_sent_after_newsletter()
    // {
    //     $this->newNewsletterData['disableNotification'] = 'true';
    //     $res = $this->post(route('newsletter.store'), $this->newNewsletterData);
    //     $log = ApplicationLog::latest()->where('description', 'Telegram Response')->first();
    //     $this->assertTrue(json_decode($log->value)->ok);
    //     $this->assertContains($this->newNewsletterData['email'], $log->value);
    //     $this->assertContains($this->newNewsletterData['name'], $log->value);
    //     $this->assertContains($this->newNewsletterData['surname'], $log->value);
    // }

    /**
     * Test Telegram notification is sent after partecipant subscription to a course
     *
     * @return void
     */
    public function test_telegram_message_is_sent_after_partecipant_subscription()
    {
        ApplicationLog::truncate();
        Queue::fake();
        $course = Course::inRandomOrder()->first();
        $this->newPartecipantData['course_id'] = $course->id;
        $this->newPartecipantData['disableNotification'] = 'true';
        $res = $this->post(route('partecipant.store'), $this->newPartecipantData);

        $log = ApplicationLog::latest()->where('description', 'Partecipant Subscription Success')->first();
        $this->assertEquals(1, $log->status);
        $this->assertContains($this->newPartecipantData['email'], $log->value);
        $this->assertContains($this->newPartecipantData['name'], $log->value);
        $this->assertContains($this->newPartecipantData['surname'], $log->value);
    }

    /**
     * Test an error is returned when no chat id is set in the config or in the env
     */
    public function test_error_is_logged_if_no_chatId_is_found_in_env()
    {
        app('config')->set('app.telegram.chat_id', null);
        $client = new \GuzzleHttp\Client();
        $telegram = new Telegram($client);
        $response = $telegram->alert('text', true);
        $this->assertStringContainsString('no chat id selected', json_encode($response));
    }

    public function test_telegram_integration()
    {
        $guzzle = Mockery::mock(\GuzzleHttp\Client::class);
        $chatId = app('config')->get('app.telegram.chat_id');

        $url = env('TELEGRAM_URI') . 'bot' . env('TELEGRAM_TOKEN') . '/sendMessage?&disable_notification=true&parse_mode=markdown&&chat_id=' . $chatId . '&text=text';

        $guzzle->shouldReceive('request')
            ->with('GET', $url, ['Accept' => 'application/json'])
            ->andReturn(new Response);

        $telegram = new Telegram($guzzle);
        $telegram->alert('text', true);

        $this->addToAssertionCount(1);
    }
}
