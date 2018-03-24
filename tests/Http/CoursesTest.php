<?php

namespace Tests\Feature;

use App\Course;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoursesTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        Parent::setUp();
        $this->user = factory('App\User')->create();

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
     * 
     *
     * @return void
     */
    // public function test_()
    // {
        // $response = $this->get('courses');
        // $response->assertJsonFragment();
    // }
}
