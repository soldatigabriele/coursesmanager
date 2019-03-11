<?php

namespace Tests\Feature;

use App\User;
use App\Coupon;
use App\Course;
use App\Region;
use Faker\Factory;
use Tests\TestCase;
use App\Partecipant;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartecipantsControllerTest extends TestCase
{
    use RefreshDatabase;

   /**
     * Test a user can delete a partecipant from a course
     *
     * @return void
     */
    public function test_delete()
    {
        $this->withoutExceptionHandling();
        
        $this->actingAs(factory(User::class)->create());
        $course = factory('App\Course')->create(['user_id' => auth()->id()]);
        $course->partecipants()->saveMany(factory('App\Partecipant', 10)->create());
        
        $partecipant = $course->partecipants->random()->first();

        $this->assertEquals(10, $course->partecipants()->count());
        
        $response = $this->delete(route('partecipant.destroy', $partecipant))->assertStatus(302);
        
        $this->assertEquals(9, $course->partecipants()->count());
    }

   /**
     * Test a user can restore a deleted partecipant from a course
     *
     * @return void
     */
    public function test_restore()
    {
        $this->withoutExceptionHandling();
        
        $this->actingAs(factory(User::class)->create());
        $course = factory('App\Course')->create(['user_id' => auth()->id()]);
        $course->partecipants()->saveMany(factory('App\Partecipant', 10)->create());
        
        $partecipant = Partecipant::latest('id')->first();

        $this->assertEquals(10, $course->partecipants()->count());
        
        $response = $this->delete(route('partecipant.destroy', $partecipant))->assertStatus(302);
        
        $this->assertEquals(9, $course->partecipants()->count());
        
        $response = $this->post(route('partecipant.restore', $partecipant))->assertStatus(302);
        $this->assertEquals(10, $course->partecipants()->count());
    }
}
