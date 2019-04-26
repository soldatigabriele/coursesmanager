<?php

namespace Tests\Feature;

use App\Course;
use Carbon\Carbon;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoursesTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->user = factory('App\User')->create();
        $this->faker = Factory::create('it_IT');
    }

    /**
     * Index request shows all courses
     *
     * @return void
     */
    public function test_index()
    {
        factory('App\Course', 10)->create()->each(function ($u) {
            $u->partecipants()->saveMany(factory('App\Partecipant', 10)->create());
        });
        $this->actingAs($this->user);
        $response = $this->get('/courses');
        $response->assertStatus(200)
            ->assertSee(Course::first()->description);
    }

    /**
     * Authorised user can create a course
     *
     * @return void
     */
    public function test_auth_user_can_create_course()
    {
        $this->actingAs($this->user);
        $courseData = factory('App\Course')->make()->toArray();
        $courseData['start_date'] = '10/10/2000';
        $courseData['end_date'] = '10/10/2000';
        $this->post(route('courses.store'), $courseData)->assertStatus(302);
        $this->assertEquals($courseData['description'], Course::latest()->first()->description);
    }

    /**
     * Authorised user can update a course
     *
     * @return void
     */
    public function test_auth_user_can_update_course()
    {
        $this->actingAs($this->user);
        $course = factory('App\Course')->create(['description' => 'old_description']);
        $courseData = $course->toArray();
        $courseData['description'] = $this->faker->sentence;
        $start = (Carbon::parse($courseData['start_date'])->format('d/m/Y'));
        $end = (Carbon::parse($courseData['end_date'])->format('d/m/Y'));
        $courseData['start_date'] = $start;
        $courseData['end_date'] = $end;
        $res = $this->put(route('courses.update', $course->id), $courseData);

        $this->assertEquals($courseData['description'], $course->fresh()->description);
    }

    /**
     * Test the right headers are retrieved
     *
     * @return void
     */
    public function test_course_headers()
    {
        $course = factory('App\Course')->create(['description' => 'old_description']);
        $data = [
            'food' => 'vegetariano',
            'transport' => 'treno',
        ];
        $partecipant = factory('App\Partecipant')->create(['data' => json_encode($data)]);
        $course->partecipants()->save($partecipant);
        $headers = ["nome", "cognome", "email", "telefono", "food", "transport"];
        $this->assertEquals(collect($headers)->sort()->values(), collect($course->headers())->sort()->values());

        // Create the other partecipant with a coupon
        $data['coupon'] = str_random(6);
        $partecipant = factory('App\Partecipant')->create(['data' => json_encode($data)]);
        $course->partecipants()->save($partecipant);

        array_push($headers, 'coupon');
        $this->assertEquals(collect($headers)->sort()->values(), collect($course->headers())->sort()->values());
    }

    /**
     * Test the right extra headers are retrieved
     *
     * @return void
     */
    public function test_course_extra_headers()
    {
        $course = factory('App\Course')->create(['description' => 'old_description']);
        $data = [
            'food' => 'vegetariano',
            'transport' => 'treno',
        ];
        $partecipant = factory('App\Partecipant')->create(['data' => json_encode($data)]);
        $course->partecipants()->save($partecipant);
        $headers = ["food", "transport"];
        $this->assertEquals(collect($headers)->sort()->values(), collect($course->extraHeaders())->sort()->values());

        // Create the other partecipant with a coupon
        $data['coupon'] = str_random(6);
        $partecipant = factory('App\Partecipant')->create(['data' => json_encode($data)]);
        $course->partecipants()->save($partecipant);

        array_push($headers, 'coupon');
        $this->assertEquals(collect($headers)->sort()->values(), collect($course->extraHeaders())->sort()->values());
    }

}
