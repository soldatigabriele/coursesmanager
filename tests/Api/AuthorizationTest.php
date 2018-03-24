<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizationTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        Parent::setUp();
        $this->user = factory('App\User')->create();
        $this->token = 'Bearer '.$this->user->api_token;
    }

    /**
     * Index request success
     *
     * @return void
     */
    public function test_index()
    {
        $string = str_random(30);
        $course = factory('App\Course')->create(['description'=>$string, 'user_id' => $this->user->id]);
        $response = $this->get('api/courses', ['HTTP_Authorization' => $this->token]);
        $response->assertJsonFragment([$string]);
    }

    /**
     * Index request fail for no token
     *
     * @return void
     */
    public function test_request_fails_for_no_token()
    {
        $response = $this->get('api/courses', ['HTTP_Authorization' => '']);
        $response->assertJsonFragment(['error'=>'missing_token']);
    }

    /**
     * Index request fail for no token
     *
     * @return void
     */
    public function test_request_fails_for_invalid_token()
    {
        $response = $this->get('api/courses', ['HTTP_Authorization' => 'Bearer '.str_random(30)]);
        $response->assertJsonFragment(['error'=>'invalid_token']);
    }
}
