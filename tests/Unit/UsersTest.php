<?php

namespace Tests\Unit;

use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        Parent::setUp();
        factory('App\Course', 10)->create();
        factory('App\User', 10)->create();
        $this->faker = Factory::create('it_IT');
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
        $user = factory('App\User')->create(['name'=>$string]);
        $response = $this->get('api/users', ['HTTP_Authorization' => $this->token]);
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
        $user = factory('App\User')->create(['name'=>$string]);
        $response = $this->get('api/users/'.$user->id, ['HTTP_Authorization' => $this->token]);
        $response->assertJsonFragment(['name'=>$string]);
    }
 
    /**
     * Post request
     *
     * @return void
     */
    public function test_store()
    {
        $name = strtoupper(str_random(10));
        $data['transport'] = 'car';
        $data['region'] = $this->faker->state;
        $data['job'] = $this->faker->jobTitle;
        $data['city'] = $this->faker->city;
        $data['fiscal_code'] = $this->faker->taxId;
        $data['data'] = json_encode($data);
        $user_data = [
            'name' => $name,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '3'.rand(111111111, 999999999),
            'data' => json_encode($data),
        ];
    	$response = $this->post('api/users', $user_data, ['HTTP_Authorization' => $this->token]);
        $id = (json_decode($response->getContent()))->id;
        $response->assertJsonFragment([$name]);
        $this->assertDatabaseHas('users', ['id'=>$id, 'name' => $name]);
    }


    /**
     * edit request
     *
     * @return void
     */
    public function test_edit()
    {
        $name = strtoupper(str_random(5));
        $user = factory('App\User')->create(['name' => $name,]);
        $new_name = strtoupper(str_random(5));
        $data = ['name' => $new_name];
        $response = $this->put('api/users/'.$user->id, $data, ['HTTP_Authorization' => $this->token]);
        $response->assertJsonFragment([$new_name]);
    }

    /**
     * Delete
     *
     * @return void
     */
    public function test_delete()
    {
        $user = factory('App\User')->create();
        $response = $this->delete('api/users/'.$user->id, [], ['HTTP_Authorization' => $this->token]);
        $this->assertSoftDeleted('users', ['id'=>$user->id]);
    }
}
