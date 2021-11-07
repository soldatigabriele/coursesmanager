<?php

namespace Tests\Http;

use App\Course;
use App\Region;
use Faker\Factory;
use Tests\TestCase;
use App\Partecipant;
use App\ApplicationLog;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoggingTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
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

        factory('App\Course', 10)->create();
    }

    public function test_successful_partecipant_creation()
    {
        Queue::fake();
        $course = Course::inRandomOrder()->first();
        $this->newPartecipantData['course_id'] = $course->id;
        $res = $this->post(route('partecipant.store'), $this->newPartecipantData);

        $newPartecipant = Partecipant::where('phone', $this->newPartecipantData['phone'])->first();
        $log = ApplicationLog::latest()->first();

        $this->assertEquals(1, $log->status);
        $this->assertEquals($log->description, 'Partecipant Subscription Success');
        $this->assertContains((string) $course->id, $log->value);
        $this->assertContains($this->newPartecipantData['slug'], $log->value);
        $this->assertContains($this->newPartecipantData['job'], $log->value);
        $this->assertContains($this->newPartecipantData['surname'], $log->value);
        $this->assertContains($this->newPartecipantData['phone'], $log->value);
        $this->assertContains($this->newPartecipantData['email'], $log->value);
        $this->assertContains($this->newPartecipantData['name'], $log->value);
    }

    public function test_unsuccessful_partecipant_creation_empty_fields()
    {
        $res = $this->post(route('partecipant.store'), []);
        $newPartecipant = Partecipant::where('phone', $this->newPartecipantData['phone'])->first();
        $log = ApplicationLog::latest()->first();

        $this->assertEquals(0, $log->status);
        $this->assertEquals($log->description, 'Partecipant Subscription Error');

        $messages = [
            'name.required' => 'Inserire un nome valido',
            'surname.required' => 'Inserire una cognome valido',
            'phone.required' => 'Inserire un numero di telefono valido',
            'job.required' => 'Inserire una professione valida',
            // 'city.required' => 'Inserire la propria provenienza',
            'email.required' => 'Inserire un indirizzo email',
        ];
        foreach ($messages as $key => $value) {
            $this->assertTrue(collect($log->value['errors'])->flatten()->contains($value));
        }
    }

    public function test_unsuccessful_partecipant_creation_mails()
    {
        $this->newPartecipantData['email'] = $this->faker->unique()->safeEmail;
        $this->newPartecipantData['email_again'] = $this->faker->unique()->safeEmail;
        $res = $this->post(route('partecipant.store'), $this->newPartecipantData);

        $newPartecipant = Partecipant::where('phone', $this->newPartecipantData['phone'])->first();
        $log = ApplicationLog::latest()->first();

        $this->assertEquals(0, $log->status);
        $this->assertEquals($log->description, 'Partecipant Subscription Error');

        $messages = [
            'email_again.same' => 'Le email non coincidono',
        ];
        foreach ($messages as $key => $value) {
            $this->assertTrue(collect($log->value['errors'])->flatten()->contains($value));
        }
    }

    public function test_unsuccessful_partecipant_creation_mail_incorrect()
    {
        $this->newPartecipantData['email'] = $this->faker->name;
        $res = $this->post(route('partecipant.store'), $this->newPartecipantData);

        $newPartecipant = Partecipant::where('phone', $this->newPartecipantData['phone'])->first();
        $log = ApplicationLog::latest()->first();

        $this->assertEquals(0, $log->status);
        $this->assertEquals($log->description, 'Partecipant Subscription Error');

        $messages = [
            'email.email' => 'Inserire un indirizzo email valido',
        ];
        foreach ($messages as $key => $value) {
            $this->assertTrue(collect($log->value['errors'])->flatten()->contains($value));
        }
    }

}
