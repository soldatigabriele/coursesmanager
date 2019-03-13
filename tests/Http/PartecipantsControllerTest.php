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

   /**
     * Test deleted partecipants list
     *
     * @return void
     */
    public function test_show_list_of_deleted_partecipants()
    {
        $this->actingAs(factory(User::class)->create());

        $activePartecipants = factory('App\Partecipant', random_int(0, 10))->create();
        $deletedPartecipants = factory('App\Partecipant', random_int(0, 10))->create(['deleted_at' => now()]);
        
        $response = $this->get(route('partecipant.deleted'))->assertStatus(200);
        
        // Check that only the deleted partecipants are returned
        $responsePartecipants = $response->original->getData()['partecipants'];
        $this->assertCount($deletedPartecipants->count(), $responsePartecipants);
        $deletedPartecipants->each(function ($partecipant) use ($responsePartecipants) {
            $this->assertTrue($responsePartecipants->contains($partecipant));
        });
        $activePartecipants->each(function ($partecipant) use ($responsePartecipants) {
            $this->assertFalse($responsePartecipants->contains($partecipant));
        });
    }
}
