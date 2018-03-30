<?php

namespace Tests\Feature;

use App\Course;
use App\Region;
use Carbon\Carbon;
use Faker\Factory;
use App\Newsletter;
use Tests\TestCase;
use App\Partecipant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegionFilterTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp()
    {
        Parent::setUp();
        $this->user = factory('App\User')->create();
        $this->faker = Factory::create('it_IT');

    }

    /**
     * Region filter in the mail page
     *
     * @return void
     */
    public function test_region_filter_in_mail_page()
    {
        $courses = factory('App\Course')->create(['user_id' => $this->user->id])
        ->each(function($u){
            $u->partecipants()->saveMany(factory('App\Partecipant', 2)->create(['region_id'=>1]));
            $u->partecipants()->saveMany(factory('App\Partecipant', 2)->create(['region_id'=>2]));
        });
        $news_one = factory('App\Newsletter', 3)->create(['name'=>'NEWS','region_id'=>1]);
        $news_two = factory('App\Newsletter', 3)->create(['name'=>'NewsZZZ','region_id'=>2]);

        $this->actingAs($this->user);
        $res = $this->get(route('partecipant-index', ['find'=>'Ricerca', 'region_id' => 1]));

        $partecipants_one = Partecipant::where('region_id', 1)->get();
        $partecipants_two = Partecipant::where('region_id', 2)->get();
        
        foreach($partecipants_one as $p){
            $res->assertSee($p->name);
        }
        foreach($partecipants_two as $p){
            $res->assertDontSee($p->name);
        }
        foreach($news_one as $p){
            $res->assertSee($p->name);
        }
        foreach($news_two as $p){
            $res->assertDontSee($p->name);
        }
        // $res->assertSee('');
    }
}