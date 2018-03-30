<?php

namespace Tests\Feature;

use Mockery;
use App\Course;
use App\Region;
use Faker\Factory;
use App\Newsletter;
use Tests\TestCase;
use App\Partecipant;
use App\ApplicationLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TelegramIntegrationTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        Parent::setUp();
        $this->user = factory('App\User')->create();

        $this->faker = Factory::create('it_IT');

        $data = [];
        $data['job'] = $this->faker->jobTitle;
        $trans = ['auto', 'treno', 'bici'];
        $data['transport'] = $trans[mt_rand(0, count($trans) - 1)];
        $source = ['facebook', 'sito', 'amici'];
        $data['source'] = $source[mt_rand(0, count($source) - 1)];
        $shares = ['si', 'no'];
        $data['shares'] = $shares[mt_rand(0, count($shares) - 1)];
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
            'phone' => '3'.rand(111111111, 999999999),
            'job' => $data['job'],
            'city' => $data['city'],
            'meta' => json_encode(['ip'=>'127.0.0.2']),
        ];

        $this->newNewsletterData = [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'region_id' => Region::inRandomOrder()->first()->id,
            'active' => 1,
            'meta' => json_encode(['ip'=>'127.0.0.2']),
        ];
        factory('App\Course', 10)->create();
    }

    public function tearDown()
    {
        // Mockery::close();
    }




    public function test_telegram_message_is_sent_after_newsletter()
    {
        $this->newNewsletterData['disableNotification'] = true;
        $this->newNewsletterData['testTelegramMessages'] = true;
        $res = $this->post(route('newsletter-store'), $this->newNewsletterData );
        $log = ApplicationLog::latest()->where('description', 'Telegram Response')->first();
        $this->assertTrue(json_decode($log->value)->ok);
        $this->assertContains($this->newNewsletterData['email'], $log->value);
        $this->assertContains($this->newNewsletterData['name'], $log->value);
        $this->assertContains($this->newNewsletterData['surname'], $log->value);
    }


    public function test_telegram_message_is_sent_after_partecipant_subscription()
    {
        $course = Course::inRandomOrder()->first();
        $this->newPartecipantData['course_id'] = $course->id;
        $this->newPartecipantData['disableNotification'] = true;
        $this->newPartecipantData['testTelegramMessages'] = true;
        $res = $this->post(route('partecipant-store'), $this->newPartecipantData );
        $log = ApplicationLog::latest()->where('description', 'Partecipant Subscription Success')->first();
        $this->assertEquals(1, $log->status);
        $this->assertContains($this->newPartecipantData['email'], $log->value);
        $this->assertContains($this->newPartecipantData['name'], $log->value);
        $this->assertContains($this->newPartecipantData['surname'], $log->value);
    }


    // public function test_telegram_message_is_sent()
    // {
//         $c = Course::inRandomOrder()->first();
//         $this->newPartecipantData['course_id'] = $c->id;
//         $this->newPartecipantData['disableNotification'] = true;
//         $this->newPartecipantData['testTelegramMessages'] = true;

//         // mock telegram class
//         $mock =  Mockery::mock('App\Helpers\Telegram');
        
//         $mock->shouldReceive('alert')
//             ->once()
//             ->andReturn('json');

//         $url = url(route('course-index'));
//         $text = '*'.$this->newPartecipantData['name'].' '.$this->newPartecipantData['surname'].'* - *'.$this->newPartecipantData['email'].'* *'.$this->newPartecipantData['phone'].'* si è iscritto al corso *'.$c->long_id.'* del '.$c->date.' [Vai alla scheda]('.$url.')';
//         $response = $mock->alert($text);
//         $res = $this->post(route('partecipant-store'), $this->newPartecipantData);
// dump($response);
    // }

}
