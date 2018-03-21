<?php

namespace Tests\Unit;

use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseSubscriptionTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(){
        Parent::setUp();
        factory('App\Course', 10)->create();
        factory('App\User', 10)->create();
        $this->faker = Factory::create('it_IT');
    }

    /**
     * User can subscribe
     *
     * @return void
     */
    public function test_user_can_subscribe()
    {
        $user = factory('App\User')->create();
        $course = factory('App\Course')->create();

        $response = $this->post('api/subscribe/'.$course->id, []);
        $response->assertDatabaseHas('course_user', ['user_id'=>$user->id, 'course_id' => $course->id]);

    }

}
