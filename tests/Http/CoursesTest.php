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
     * Index request shows your courses
     *
     * @return void
     */
    public function test_index()
    {
        factory('App\Course', 10)->create(['user_id' => $this->user->id])->each(function($u){
            $u->partecipants()->saveMany(factory('App\Partecipant', 10)->create());
        });
        $this->actingAs($this->user);
        $response = $this->get('/courses');
        $response->assertStatus(200)
            ->assertSee(Course::first()->description);
    }

    /**
     * Index request does not show other admin courses
     *
     * @return void
     */
    public function test_index_does_not_show_others_courses()
    {
        $course = factory('App\Course')->create(['user_id' => $this->user->id]);
        $course->partecipants()->saveMany(factory('App\Partecipant', 10)->create());
        $this->actingAs(factory('App\User')->create());
        $response = $this->get('/courses');
        $response->assertStatus(200)
            ->assertDontSee($course->description);
    }

    /**
     * Authorised user can update a course
     *
     * @return void
     */
    public function test_auth_user_can_update_course()
    {
        $this->actingAs($this->user);
        $course = factory('App\Course')->create(['description' => 'old_description', 'user_id' => $this->user->id]);
        $courseData = $course->toArray();
        $courseData['description'] = $this->faker->sentence;
        $start = (Carbon::parse($courseData['start_date'])->format('d/m/Y'));
        $end = (Carbon::parse($courseData['end_date'])->format('d/m/Y'));
        $courseData['start_date'] = $start;
        $courseData['end_date'] = $end;
        $res = $this->put(route('courses.update', $course->id), $courseData );

        $this->assertEquals($courseData['description'], $course->fresh()->description);
    }

}
