<?php

namespace Tests\Unit;

use Faker\Factory;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseSubscriptionTest extends TestCase
{

    use RefreshDatabase;

    protected $user, $course, $token;

    protected function setUp(){
        Parent::setUp();
        factory('App\Course', 10)->create();
        $this->course = factory('App\Course')->create();
        factory('App\User', 5)->create();
        $this->user = factory('App\User')->create();
        $this->manager = factory('App\Manager')->create();
        $this->token = 'Bearer '.$this->manager->api_token;

        $this->faker = Factory::create('it_IT');


    }

    /**
     * User can subscribe to a course
     *
     * @return void
     */
    public function test_user_can_subscribe_to_a_course()
    {

        $response = $this->post('api/subscribe', ['course_id' => $this->course->id, 'user_id' => $this->user->id], ['HTTP_Authorization' => $this->token]);
        $this->assertDatabaseHas('course_user', ['user_id'=>$this->user->id, 'course_id' => $this->course->id]);
    }

    /**
     * Admin can get the users subscriptions
     *
     * @return void
     */
    public function test_admin_can_get_courses_list()
    {
        $user = factory('App\User')->create();
        $long_ids = [];
        for ($i=1; $i < 10; $i++) { 
            $course = factory('App\Course')->create();
            $user->courses()->attach($course->id);
            $long_ids[] = $course->long_id;
        }
        $response = $this->get('api/getsubscriptions/'. $user->id, ['HTTP_Authorization' => $this->token]);
        foreach($long_ids as $long_id){
            $response->assertJsonFragment([ $long_id ]);
        }
    }

    /**
     * User can subscribe to multiple courses
     *
     * @return void
     */
    public function test_user_can_subscribe_to_multiple_courses()
    {
        for ($i=1; $i < 11; $i++) { 
            $response = $this->post('api/subscribe', ['course_id' => $i, 'user_id' => $this->user->id], ['HTTP_Authorization' => $this->token]);
            $this->assertDatabaseHas('course_user', [
                'user_id' => $this->user->id, 
                'course_id' => $i
            ]);
        }
    }

    /**
     * User cannot subscribe to nonexisting courses
     *
     * @return void
     */
    public function test_user_cannot_subscribe_to_not_existing_courses()
    {
        $response = $this->post('api/subscribe', ['course_id' => 999, 'user_id' => $this->user->id], ['HTTP_Authorization' => $this->token]);
        $this->assertDatabaseMissing('course_user', [
            'user_id' => $this->user->id, 
            'course_id' => 999
        ]);

    }

}
