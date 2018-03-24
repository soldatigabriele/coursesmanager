<?php

namespace Tests\Unit;

use Faker\Factory;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CourseSubscriptionTest extends TestCase
{

    use RefreshDatabase, WithoutMiddleware;

    protected $partecipant, $course, $token;

    protected function setUp()
    {
        Parent::setUp();
        factory('App\Course', 10)->create();
        $this->course = factory('App\Course')->create();
        factory('App\Partecipant', 5)->create();
        $this->partecipant = factory('App\Partecipant')->create();
        $this->user = factory('App\User')->create();
        $this->token = 'Bearer '.$this->user->api_token;
        $this->faker = Factory::create('it_IT');
    }

    /**
     * partecipant can subscribe to a course
     *
     * @return void
     */
    public function test_partecipant_can_subscribe_to_a_course()
    {
        $response = $this->post('api/subscribe', ['course_id' => $this->course->id, 'partecipant_id' => $this->partecipant->id]);
        $this->assertDatabaseHas('course_partecipant', ['partecipant_id'=>$this->partecipant->id, 'course_id' => $this->course->id]);
    }

    /**
     * Admin can get the partecipants subscriptions
     *
     * @return void
     */
    public function test_admin_can_get_courses_list()
    {
        $partecipant = factory('App\Partecipant')->create();
        $long_ids = [];
        for ($i=1; $i < 10; $i++) { 
            $course = factory('App\Course')->create();
            $partecipant->courses()->attach($course->id);
            $long_ids[] = $course->long_id;
        }
        $response = $this->get('api/getsubscriptions/'. $partecipant->id);
        foreach($long_ids as $long_id){
            $response->assertJsonFragment([ $long_id ]);
        }
    }

    /**
     * partecipant can subscribe to multiple courses
     *
     * @return void
     */
    public function test_partecipant_can_subscribe_to_multiple_courses()
    {
        for ($i=1; $i < 11; $i++) { 
            $response = $this->post('api/subscribe', ['course_id' => $i, 'partecipant_id' => $this->partecipant->id]);
            $this->assertDatabaseHas('course_partecipant', [
                'partecipant_id' => $this->partecipant->id, 
                'course_id' => $i
            ]);
        }
    }

    /**
     * partecipant cannot subscribe to nonexisting courses
     *
     * @return void
     */
    public function test_partecipant_cannot_subscribe_to_not_existing_courses()
    {
        $response = $this->post('api/subscribe', ['course_id' => 999, 'partecipant_id' => $this->partecipant->id]);
        $this->assertDatabaseMissing('course_partecipant', [
            'partecipant_id' => $this->partecipant->id, 
            'course_id' => 999
        ]);
    }
}
