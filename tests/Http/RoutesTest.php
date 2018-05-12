<?php

namespace Tests\Feature;

use App\Course;
use App\Region;
use Faker\Factory;
use App\Newsletter;
use Tests\TestCase;
use App\Partecipant;
use App\ApplicationLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoutesTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        Parent::setUp();
        $this->user = factory('App\User')->create();
        $this->course = factory('App\Course')->create();
        $this->partecipant = factory('App\Partecipant')->create();
        $this->newsletter = factory('App\Newsletter')->create();

        $this->faker = Factory::create('it_IT');

        $data = [];
        $data['job'] = $this->faker->jobTitle;
        $trans = ['auto', 'treno', 'bici'];
        $data['transport'] = $trans[mt_rand(0, count($trans) - 1)];
        $source = ['facebook', 'sito', 'amici'];
        $data['source'] = $source[mt_rand(0, count($source) - 1)];
        $data['shares'] = random_int(0, 1);
        $data['city'] = $this->faker->city;
        $data['fiscal_code'] = $this->faker->taxId;
        $food = ['veget', 'vegano', 'onnivoro'];
        $data['food'] = $food[mt_rand(0, count($food) - 1)];
        $email = $this->faker->unique()->safeEmail;

        $this->newPartecipantData = [
            'slug' => str_random(20),
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'region_id' => Region::inRandomOrder()->first()->id,
            'email' => $email,
            'email_again' => $email,
            'phone' => '3'.rand(111111111, 999999999),
            'job' => $data['job'],
            'city' => $data['city'],
            'meta' => json_encode(['ip'=>'127.0.0.2']),
        ];

        $this->newNewsletterData = [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'region_id' => Region::inRandomOrder()->first()->id,
            'active' => 1,
            'meta' => json_encode(['ip'=>'127.0.0.2']),
        ];
        factory('App\Course', 10)->create();
    }


    /**
     * Unathorised user can see certain routes
     *
     * @return void
     */
    public function test_unath_user_can_see_certain_pages()
    {
        
        $this->get(route('scheda-1'))        
            ->assertStatus(200);
        
        $this->get(route('scheda-2'))
            ->assertStatus(200);

        $this->get(route('partecipant-show', $this->partecipant->slug))
            ->assertStatus(200);

        $res = $this->get(route('partecipant-show', 'non_existing_slug'))
            ->assertStatus(404);

        $this->get(route('newsletter-create'))
            ->assertStatus(200);

        $this->get(route('newsletter-show', $this->newsletter->slug))
            ->assertStatus(200);
            
        $res = $this->get(route('newsletter-show', 'non_existing_slug'))
            ->assertStatus(404);
    }

    /**
     * Unathorised user dont reach protected routes for newsletter
     *
     * @return void
     */
    public function test_unath_user_dont_reach_protected_newsletter_routes()
    {
        $this->get(route('newsletter-index'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /**
     * Unathorised user dont reach protected routes
     *
     * @return void
     */
    public function test_unath_user_dont_reach_protected_partecipant_routes()
    {
        $name = $this->partecipant->name;

        $this->get(route('partecipant-index'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        
        $this->get(route('partecipant-create'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        
        $this->delete(route('partecipant-destroy', $this->partecipant->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertEquals($this->partecipant->deleted_at, null);
        
        $this->get(route('partecipant-edit', $this->partecipant->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        
        $this->put(route('partecipant-update', $this->partecipant->id), [])
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertEquals($this->partecipant->name, $name);
    }

    public function test_unath_user_dont_reach_protected_courses_routes()
    {

        $this->get(route('courses.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        
        $this->get(route('courses.show', $this->course->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        
        $this->get(route('courses.create'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->post(route('courses.store'), [])
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->delete(route('courses.destroy', $this->course->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->get(route('courses.edit', $this->course->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->put(route('courses.update', $this->course->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->get(route('courses.export', $this->course->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        // Doesnt exist yet
        // $this->get(route('partecipant-edit', $this->partecipant->slug))
        //     ->assertStatus(200);
    }

    /**
     * Authorised user can see protected get routes
     *
     * @return void
     */
    public function test_auth_user_can_see_protected_get_routes()
    {        
        $this->actingAs($this->user);
        
        $this->get(route('partecipant-index'))
            ->assertStatus(200);
        
        $this->get(route('partecipant-show', $this->partecipant->slug))
            ->assertStatus(200);
        
        $this->get(route('partecipant-create'))
            ->assertStatus(200);
        
        $this->get(route('courses.index'))
            ->assertStatus(200);
        
        $this->get(route('courses.show', $this->course->id))
            ->assertStatus(200);
        
        $this->get(route('courses.create'))
            ->assertStatus(200);

        $this->get(route('courses.edit', $this->course->id))
            ->assertStatus(200);

        $this->get(route('courses.export', $this->course->id))
            ->assertStatus(200);

        $this->get(route('newsletter-index'))
            ->assertStatus(200);
        
        $this->get(route('newsletter-create'))
            ->assertStatus(200);
    }

    public function test_update_new_partecipant()
    {
        $this->actingAs($this->user);
        $this->put(route('partecipant-update', $this->partecipant->id), $this->newPartecipantData);
        $this->assertEquals($this->partecipant->fresh()->email, $this->newPartecipantData['email']);
    }

    public function test_destroy_partecipant()
    {
        $this->actingAs($this->user);

        $this->delete(route('partecipant-destroy', $this->partecipant->id))
            ->assertStatus(200);
        $this->assertNotEquals($this->partecipant->fresh()->deleted_at, null);
    }

    
    /**
     * Index request does not show other admin courses
     *
     * @return void
     */
    // public function test_index_does_not_show_others_courses()
    // {
    //     $this->actingAs($this->user);
    //     $response = $this->get('/courses');
    //     $response->assertStatus(200)
    //         ->assertSee(Course::first()->description);

    //     $course = factory('App\Course')->create(['user_id' => $this->user->id]);
    //     $course->partecipants()->saveMany(factory('App\Partecipant', 10)->create());
    //     $this->actingAs(factory('App\User')->create());
    //     $response = $this->get('/courses');
    //     $response->assertStatus(200)
    //         ->assertDontSee($course->description);
    // }

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
