<?php

namespace Tests\Unit;

use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartecipantsTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        Parent::setUp();
        factory('App\Course', 10)->create();
        factory('App\Partecipant', 10)->create();
        $this->faker = Factory::create('it_IT');
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
        $string = str_random(30);
        $partecipant = factory('App\Partecipant')->create(['name'=>$string]);
        $response = $this->get('api/partecipants', ['HTTP_Authorization' => $this->token]);
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
        $partecipant = factory('App\Partecipant')->create(['name'=>$string]);
        $response = $this->get('api/partecipants/'.$partecipant->id, ['HTTP_Authorization' => $this->token]);
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
        $partecipant_data = [
            'name' => $name,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '3'.rand(111111111, 999999999),
            'data' => json_encode($data),
        ];
    	$response = $this->post('api/partecipants', $partecipant_data, ['HTTP_Authorization' => $this->token]);
        // dd($response->getContent());
        $id = (json_decode($response->getContent()))->id;
        $response->assertJsonFragment([$name]);
        $this->assertDatabaseHas('partecipants', ['id'=>$id, 'name' => $name]);
    }


    /**
     * edit request
     *
     * @return void
     */
    public function test_edit()
    {
        $name = strtoupper(str_random(5));
        $partecipant = factory('App\Partecipant')->create(['name' => $name]);
        $new_name = strtoupper(str_random(5));
        $data = ['name' => $new_name];
        $response = $this->put('api/partecipants/'.$partecipant->id, $data, ['HTTP_Authorization' => $this->token]);
        $response->assertJsonFragment([$new_name]);
    }

    /**
     * Delete
     *
     * @return void
     */
    public function test_delete()
    {
        $partecipant = factory('App\Partecipant')->create();
        $response = $this->delete('api/partecipants/'.$partecipant->id, [], ['HTTP_Authorization' => $this->token]);
        $this->assertSoftDeleted('partecipants', ['id'=>$partecipant->id]);
    }
}
