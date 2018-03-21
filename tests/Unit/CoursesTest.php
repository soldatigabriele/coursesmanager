<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoursesTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(){

        Parent::setUp();
        factory('App\Course', 10)->create();
        factory('App\User', 10)->create();
        $this->manager = factory('App\Manager')->create();
        $this->token = 'Bearer '.$this->manager->api_token;

    }

    /**
     * Index request
     *
     * @return void
     */
    public function test_index()
    {
        $string = str_random(30);
        $course = factory('App\Course')->create(['description'=>$string]);
        $response = $this->get('api/courses', ['HTTP_Authorization' => $this->token]);
        $response->assertJsonFragment([$string]);

    }

     /**
     * Show request
     *
     * @return void
     */
    public function test_show()
    {
        $string = str_random(30);
        $course = factory('App\Course')->create(['description'=>$string]);
        $response = $this->get('api/courses/'.$course->id, ['HTTP_Authorization' => $this->token]);
        $response->assertJsonFragment(['description'=>$string]);
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
        'limit' => rand(1, 20) ];
    	$response = $this->post('api/courses', $data, ['HTTP_Authorization' => $this->token]);
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
