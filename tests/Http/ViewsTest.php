<?php

namespace Tests\Http;

use App\Course;
use Carbon\Carbon;
use Faker\Factory;
use Tests\TestCase;
use App\Partecipant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewsTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory('App\User')->create();
        $this->faker = Factory::create('it_IT');
    }

    /**
     * New partecipants are shown in the homepage
     *
     * @return void
     */
    public function test_new_partecipants_are_shown_in_homepage()
    {
        $partecipants = collect([]);
        $partecipants = $partecipants->merge(factory('App\Partecipant', 3)->create(['created_at' => Carbon::now()]));

        $course = factory('App\Course')->create();
        $partecipants->each(function ($item) use ($course) {
            $course->partecipants()->save($item);

        });

        $this->actingAs($this->user);
        $res = $this->get(route('home'))
            ->assertStatus(200);
        foreach ($partecipants as $p) {
            $res->assertSee($p->name)
                ->assertSee($p->email)
                ->assertSee($p->phone)
                ->assertSee($course->long_id);
        }
    }

    /**
     * New partecipants are shown in the mail page
     *
     * @return void
     */
    public function test_partecipants_are_shown_in_mail_page()
    {
        $partecipants = collect([]);
        $partecipants = factory('App\Partecipant')->create(['email' => 'test@test.com']);
        $course = factory('App\Course')->create();
        $partecipants->each(function ($item) use ($course) {
            $course->partecipants()->save($item);
        });
        $this->actingAs($this->user);
        $res = $this->get(route('partecipant.index'))
            ->assertStatus(200);
        $res->assertSee('test@test.com');
    }

    /**
     * Auth user sees tables in courses index page
     *
     * @return void
     */
    public function test_auth_user_see_tables_in_courses_index_page()
    {
        $user = factory('App\User')->create();
        $courses = factory('App\Course', 10)->create()->each(function ($u) {
            $u->partecipants()->saveMany(factory('App\Partecipant', random_int(0, 10))->create());
        });

        $this->actingAs($user);
        $res = $this->get(route('courses.index'));

        foreach ($courses as $c) {
            $res->assertSee($c->long_id);
        }
    }

    /**
     * Old courses are not displayed in courses index
     *
     * @return void
     */
    public function test_old_courses_are_not_displayed_in_courses_index_page()
    {
        $user = factory('App\User')->create();
        $course = factory('App\Course')->create(['end_date' => Carbon::now()->addDays(10)]);
        $tomorrow_course = factory('App\Course')->create(['end_date' => Carbon::tomorrow()]);
        $old_course = factory('App\Course')->create(['end_date' => Carbon::now()->subDays(10)]);
        $this->actingAs($user);
        $res = $this->get(route('courses.index'));

        $res->assertSee($course->long_id);
        $res->assertSee($tomorrow_course->long_id);
        $res->assertDontSee($old_course->long_id);
    }

    /**
     * Old courses are not displayed in courses list scheda
     *
     * @return void
     */
    public function test_old_courses_are_not_displayed_in_scheda_courses_select()
    {
        $user = factory('App\User')->create();
        $future_course = factory('App\Course')->create(['end_date' => Carbon::now()->addDays(10)]);
        $tomorrow_course = factory('App\Course')->create(['end_date' => Carbon::tomorrow()]);
        $yesterday_course = factory('App\Course')->create(['end_date' => Carbon::yesterday()]);
        $past_course = factory('App\Course')->create(['end_date' => Carbon::now()->subDays(10)]);
        $this->actingAs($user);
        $routes = ['scheda-1', 'scheda-2'];
        foreach ($routes as $r) {
            $res = $this->get(route($r));
            $res->assertSee($future_course->long_id);
            $res->assertSee($tomorrow_course->long_id);
            $res->assertDontSee($yesterday_course->long_id);
            $res->assertDontSee($past_course->long_id);
        }
    }

    /**
     * Auth users can see export table and right values are shown or hidden
     *
     * @return void
     */
    public function test_auth_user_can_see_export_tables()
    {
        $user = factory('App\User')->create();
        $phone = random_int(1111111111, 9999999999);
        $phone2 = random_int(1111111111, 9999999999);
        $partecipantShares = factory('App\Partecipant')->create(
            ['data' => json_encode([
                'shares' => 1,
            ]),
            ]);
        $partecipantDoesntShare = factory('App\Partecipant')->create(
            ['data' => json_encode([
                'shares' => 0,
            ]),
            ]);

        $course = factory('App\Course')->create()->each(function ($u) use ($partecipantShares, $partecipantDoesntShare) {
            $u->partecipants()->save(
                $partecipantShares
            );
            $u->partecipants()->save(
                $partecipantDoesntShare
            );
        });

        $res = $this->actingAs($this->user)->get(route('courses.export', $course));

        $res->assertSee($partecipantDoesntShare->city);
        $res->assertSee(htmlspecialchars($partecipantDoesntShare->name, ENT_QUOTES));
        $res->assertSee(htmlspecialchars($partecipantDoesntShare->surname, ENT_QUOTES));
        $res->assertDontSee($partecipantDoesntShare->phone);
        $res->assertDontSee($partecipantDoesntShare->email);
        $res->assertSee('NON CONDIVIDE');

        $res->assertSee($partecipantShares->email);
        $res->assertSee($partecipantShares->phone);
        $res->assertSee($partecipantShares->city);
        $res->assertSee(htmlspecialchars($partecipantShares->name, ENT_QUOTES));
        $res->assertSee(htmlspecialchars($partecipantShares->surname, ENT_QUOTES));
    }
}
