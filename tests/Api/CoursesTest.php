<?php

namespace Tests\Unit;

use App\User;
use App\Course;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoursesTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        Parent::setUp();
        factory('App\Course', 10)->create();
        factory('App\Partecipant', 10)->create();
        $this->user = factory('App\User')->create();
        $this->token = 'Bearer '.$this->user->api_token;
    }

    /**
     * Index request
     *
     * @return void
     */
    public function test_index()
    {
        $descriptions = [];
        for ($i=0; $i < 10; $i++) { 
            $string = str_random(30);
            $descriptions[] = $string;
            $course = factory('App\Course')->create(['description'=>$string, 'user_id'=>$this->user->id]);
        }
        $response = $this->get('api/courses', ['HTTP_Authorization' => $this->token]);
        foreach($descriptions as $d){
            $response->assertJsonFragment([$d]);
        }
    }


    /**
     * Index request no course with user id 
     *
     * @return void
     */
    public function test_index_no_user()
    {
        $string = str_random(30);
        $course = factory('App\Course')->create(['description'=>$string]);
        $response = $this->get('api/courses', ['HTTP_Authorization' => $this->token]);
        $response->assertJsonFragment(['error'=>'No course found']);
    }

     /**
     * Show request
     *
     * @return void
     */
    public function test_show()
    {
        $string = str_random(30);
        $course = factory('App\Course')->create(['description'=>$string, 'user_id' => $this->user->id]);
        $response = $this->get('api/courses/'.$course->id, ['HTTP_Authorization' => $this->token]);
        $response->assertJsonFragment(['description'=>$string]);
    }
 

     /**
     * Show request refused
     *
     * @return void
     */
    public function test_show_refused_wrong_user()
    {
        $string = str_random(30);
        $course = factory('App\Course')->create(['description'=>$string, 'user_id' => 9999 ]);
        $response = $this->get('api/courses/'.$course->id, ['HTTP_Authorization' => $this->token]);
        $response->assertJsonFragment(['error' => 'You cannot see this resource']);
    }
 
    /**
     * Post request
     *
     * @return void
     */
    public function test_store()
    {
        $long_id = strtoupper(str_random(5));
        $data = ['long_id' => $long_id,
        'description' => str_random(30),
        'date' => date('d/m/Y').' al '.date('d/m/Y'),
        'start_date' => date('d/m/Y'),
        'end_date' => date('d/m/Y'),
        'limit' => rand(1, 20) ];
        $response = $this->post('api/courses', $data, ['HTTP_Authorization' => $this->token]);
        $course = Course::find(json_decode($response->getContent())->id);
        // check course has user id
        $this->assertEquals($course->user_id, $this->user->id);
        $this->assertEquals($course->start_date, Carbon::now());
        $response->assertJsonFragment([$long_id]);
    }


    /**
     * edit request
     *
     * @return void
     */
    public function test_edit()
    {
        $long_id = strtoupper(str_random(5));
        $course = factory('App\Course')->create(['long_id' => $long_id,]);
        $new_long_id = strtoupper(str_random(5));
        $data = ['long_id' => $new_long_id];
        $response = $this->put('api/courses/'.$course->id, $data, ['HTTP_Authorization' => $this->token]);
        $response->assertJsonFragment([$new_long_id]);
    }

    /**
     * Delete
     *
     * @return void
     */
    public function test_delete()
    {
        $course = factory('App\Course')->create();
        $response = $this->delete('api/courses/'.$course->id, [], ['HTTP_Authorization' => $this->token]);
        $this->assertSoftDeleted('courses', ['id'=>$course->id]);
    }
}
