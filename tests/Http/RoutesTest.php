<?php

namespace Tests\Feature;

use App\Course;
use App\Region;
use Faker\Factory;
use App\Newsletter;
use Tests\TestCase;
use App\Partecipant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoutesTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        Parent::setUp();
        $this->user = factory('App\User')->create();

        $this->faker = Factory::create('it_IT');

        $data = [];
        $data['job'] = $this->faker->jobTitle;
        $trans = ['auto', 'treno', 'bici'];
        $data['transport'] = $trans[mt_rand(0, count($trans) - 1)];
        $source = ['facebook', 'sito', 'amici'];
        $data['source'] = $source[mt_rand(0, count($source) - 1)];
        $shares = ['si', 'no'];
        $data['shares'] = $shares[mt_rand(0, count($shares) - 1)];
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

    }


    /**
     * Unathorised user can see certain routes
     *
     * @return void
     */
    public function test_unath_user_can_see_certain_pages()
    {
        $p = factory('App\Partecipant')->create();

        $this->get(route('partecipant-show', $p->slug))
            ->assertStatus(200);

        $res = $this->get(route('partecipant-show', 1))
            ->assertStatus(200);
        $res->assertSee('no user found');
        
        $this->get(route('scheda-1'))        
            ->assertStatus(200);
        
        $this->get(route('scheda-2'))
            ->assertStatus(200);        

        $this->get(route('newsletter-create'))
            ->assertStatus(200);
    }

    public function test_unath_user_can_subscribe_to_newsletter()
    {
        
        $res = $this->post(route('newsletter-store'), $this->newNewsletterData );
        $newNewsletter = Newsletter::where('email', $this->newNewsletterData['email'])->first();
        $this->assertInstanceOf('App\Newsletter', $newNewsletter);
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
        $p = factory('App\Partecipant')->create();
        $name = $p->name;

        $this->get(route('partecipant-index'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        
        $this->get(route('partecipant-create'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        
        $this->delete(route('partecipant-destroy', $p->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertEquals($p->deleted_at, null);

        $this->post(route('partecipant-store'), [])
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        
        $this->get(route('partecipant-edit', $p->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        
        $this->put(route('partecipant-update', $p->id), [])
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertEquals($p->name, $name);
    }

    public function test_unath_user_dont_reach_protected_courses_routes()
    {
        $c = factory('App\Course')->create();

        $this->get(route('course-index'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        
        $this->get(route('course-show', $c->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        
        $this->get(route('course-create'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->post(route('course-store'), [])
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->delete(route('course-destroy', $c->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->get(route('course-edit', $c->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->put(route('course-update', $c->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        // Doesnt exist yet
        // $this->get(route('partecipant-edit', $p->slug))
        //     ->assertStatus(200);
    }

    /**
     * Authorised user can see protected get routes
     *
     * @return void
     */
    public function test_auth_user_can_see_protected_get_routes()
    {        
        $p = factory('App\Partecipant')->create();
        $c = factory('App\Course')->create();

        $name = $p->name;
        $this->actingAs($this->user);
        
        $this->get(route('partecipant-index'))
            ->assertStatus(200);
        
        $this->get(route('partecipant-show', $p->slug))
            ->assertStatus(200);
        
        $this->get(route('partecipant-create'))
            ->assertStatus(200);
        
        $this->get(route('course-index'))
            ->assertStatus(200);
        
        $this->get(route('course-show', $c->id))
            ->assertStatus(200);
        
        $this->get(route('course-create'))
            ->assertStatus(200);

        $this->get(route('newsletter-index'))
            ->assertStatus(200);
        
        $this->get(route('newsletter-create'))
            ->assertStatus(200);
    }

    public function test_store_new_partecipant()
    {
        $this->actingAs($this->user);
        $res = $this->post(route('partecipant-store'), $this->newPartecipantData)
            ->assertSessionHas(['status' => 'Iscrizione avvenuta con successo!']);
        $this->assertInstanceOf('App\Partecipant', Partecipant::where('phone', $this->newPartecipantData['phone'])->first());
    }

    public function test_update_new_partecipant()
    {
        $this->actingAs($this->user);
        $partecipant = factory('App\Partecipant')->create();
        $this->put(route('partecipant-update', $partecipant->id), $this->newPartecipantData);
        $this->assertEquals($partecipant->fresh()->email, $this->newPartecipantData['email']);

    }

    public function test_destroy_partecipant()
    {
        $this->actingAs($this->user);
        $p = factory('App\Partecipant')->create();

        $this->delete(route('partecipant-destroy', $p->id))
            ->assertStatus(200);
        $this->assertNotEquals($p->fresh()->deleted_at, null);
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
