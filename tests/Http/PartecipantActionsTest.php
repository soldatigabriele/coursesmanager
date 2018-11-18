<?php

namespace Tests\Feature;

use App\Course;
use App\Region;
use Faker\Factory;
use App\Newsletter;
use Tests\TestCase;
use App\Partecipant;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartecipantActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->user = factory('App\User')->create();

        factory('App\Course', 10)->create();
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
            'course_id' => Course::inRandomOrder()->first()->id,
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
    }

    public function test_unath_user_can_subscribe_to_newsletter()
    {
        Queue::fake();
        $res = $this->post(route('newsletter.store'), $this->newNewsletterData);
        $newNewsletter = Newsletter::where('email', $this->newNewsletterData['email'])->first();
        $this->assertInstanceOf('App\Newsletter', $newNewsletter);
    }

    public function test_unauth_user_can_subscribe_to_a_course()
    {
        Queue::fake();
        $res = $this->post(route('partecipant.store'), $this->newPartecipantData)
            ->assertSessionHas(['status' => 'Iscrizione al corso avvenuta con successo!']);
        $newPartecipant = Partecipant::where('phone', $this->newPartecipantData['phone'])->first();
        $this->assertInstanceOf('App\Partecipant', $newPartecipant);
        $course = $newPartecipant->courses->first()->id;
        $this->assertEquals($course, $this->newPartecipantData['course_id']);
    }

    public function test_user_sees_confirmation_message_after_subscription()
    {
        // Open the partecipant show page without the message in the session
        $partecipant = factory(Partecipant::class)->create();
        $res = $this->get(route('partecipant.show', $partecipant->slug));

        $this->assertNotContains($message = 'iscrizione avvenuta con successo' ,$res->getContent());
        // Reach the same page with the message in the session
        $res = $this->withSession(['status'=> $message])
            ->get(route('partecipant.show', $partecipant->slug));

        $this->assertContains($message ,$res->getContent());
    }
}
