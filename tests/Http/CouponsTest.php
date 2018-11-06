<?php

namespace Tests\Feature;

use App\Coupon;
use App\Course;
use App\Region;
use Faker\Factory;
use Tests\TestCase;
use App\Partecipant;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CouponsTest extends TestCase
{
    use RefreshDatabase;

    protected $validCoupon;
    protected $inactiveCoupon;

    protected function setUp()
    {
        parent::setUp();
        Queue::fake();
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
            'slug' => str_random(30),
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'region_id' => Region::inRandomOrder()->first()->id,
            'email' => $email,
            'email_again' => $email,
            'phone' => '3' . rand(111111111, 999999999),
            'job' => $data['job'],
            'city' => $data['city'],
            'course_id' => Course::inRandomOrder()->first()->id ?? factory(Course::class)->create()->id,
            'meta' => json_encode(['ip' => '127.0.0.2']),
        ];

        // $this->coupon = Coupon::create(['value' => strtoupper(str_random(5)), 'active' => true]);
        $this->coupon = factory(Coupon::class)->create();
        // $this->inactiveCoupon = Coupon::create(['value' => strtoupper(str_random(5)), 'active' => false]);
        $this->inactiveCoupon = factory(Coupon::class)->create(['active' => false]);
    }

    /**
     * Test the coupon value is capitalised when created
     *
     * @return void
     */
    public function test_coupon_mutator()
    {
        $coupon = factory(Coupon::class)->make();
        $this->assertEquals(strtoupper($coupon->value), $coupon->value);        
    }

    /**
     * Check that a coupon is valid and set in the session
     *
     * @return void
     */
    public function test_valid_coupon_check()
    {
        // Valid coupon
        $this->assertNull(session()->get('coupon'));
        // Check the validity of the coupon
        $res = $this->get(route('coupon.check', ['coupon' => $this->coupon->value, 'course_id' => $this->coupon->course->id]));
        $this->assertEquals('ok', json_decode($res->getContent())->status);
        $this->assertTrue(session()->has('coupon'));
        $this->assertEquals($this->coupon->value, session()->get('coupon'));
    }

    /**
     * Check that a coupon is unset from the session
     *
     * @return void
     */
    public function test_coupon_unset()
    {
        // Valid coupon
        $res = $this->get(route('coupon.check', ['coupon' => $this->coupon->value, 'course_id' => $this->coupon->course->id]));
        $this->assertTrue(session()->has('coupon'));
        $this->assertTrue(session()->has('course_id'));
        // Unset the coupon
        $res = $this->get(route('coupon.unset'));
        $this->assertEquals('ok', json_decode($res->getContent())->status);
        $this->assertNull(session()->get('coupon'));
        $this->assertNull(session()->get('course_id'));
    }

    /**
     * Check that a coupon is valid but course is different
     *
     * @return void
     */
    public function test_valid_coupon_wrong_course_check()
    {
        // Valid coupon
        $this->assertNull(session()->get('coupon'));
        // Check the validity of the coupon
        $res = $this->get(route('coupon.check', ['coupon' => $this->coupon->value, 'course_id' => ($this->coupon->course->id + 1)]));
        $this->assertEquals('ko', json_decode($res->getContent())->status);
        $this->assertFalse(session()->has('coupon'));
    }

    /**
     * Test a check with an invalid coupon returns ko
     *
     * @return void
     */
    public function test_invalid_coupon_check()
    {
        // Inactive coupon
        $this->assertNull(session()->get('coupon'));
        $data = ['coupon' => $this->inactiveCoupon->value, 'course_id' => $this->coupon->course->id];
        $res = $this->get(route('coupon.check', $data));
        $this->assertEquals('ko', json_decode($res->getContent())->status);
        $this->assertFalse(session()->has('coupon'));

    }

    /**
     * Test a check with an inactive coupon returns ko
     *
     * @return void
     */
    public function test_inactive_coupon_check()
    {
        // Invalid coupon
        $this->assertNull(session()->get('coupon'));
        $res = $this->get(route('coupon.check', ['coupon' => 'InvalidCoupon', 'course_id' => $this->coupon->course->id]));
        $this->assertEquals('ko', json_decode($res->getContent())->status);
        $this->assertFalse(session()->has('coupon'));
    }

    /**
     * Test coupons are checked before subscribing user: valid
     *
     * @return void
     */
    public function test_check_valid_coupon_on_subscription()
    {
        $res = $this->withSession(['coupon' => $this->coupon->value])->post(route('partecipant.store'), $this->newPartecipantData)
            ->assertSessionHas(['status' => 'Iscrizione avvenuta con successo!']);

        // Assert the coupons are equals
        $partecipant = Partecipant::where('phone', $this->newPartecipantData['phone'])->first();
        $this->assertEquals($this->coupon->value, $partecipant->getCoupon());

        // Check that the used coupon is in the summary page
        $res = $this->get(route('partecipant.show', ['slug' => $partecipant->slug]));
        $this->assertContains($this->coupon->value, $res->getContent());
    }

    /**
     * Test a new coupon is created on subscribing user if the create_coupon hidden field is in the form
     *
     * @return void
     */
    public function test_a_new_coupon_is_created_on_subscription_if_right_form_is_compiled()
    {
        $this->newPartecipantData["create_coupon"] = true;
        $res = $this->post(route('partecipant.store'), $this->newPartecipantData)
            ->assertSessionHas(['status' => 'Iscrizione avvenuta con successo!']);

        $partecipant = Partecipant::where('phone', $this->newPartecipantData['phone'])->first();
        // Check a new Coupon is created for that user and course
        $courseId = $this->newPartecipantData['course_id'];
        // Get the coupon
        $coupon = $partecipant->personalCoupon;
        $this->assertInstanceOf(Coupon::class, $coupon);
        // Check that the coupon is associated with the right course
        $this->assertEquals($coupon->course->id, $courseId);
        $res = $this->get(route('partecipant.show', ['slug' => $partecipant->slug]));
        $this->assertContains($coupon->value, $res->getContent());
    }

    /**
     * Test a new coupon is created on subscribing user
     *
     * @return void
     */
    public function test_a_new_coupon_is_created_on_subscription()
    {
        $this->assertFalse(isset($this->newPartecipantData["create_coupon"]));
        $res = $this->post(route('partecipant.store'), $this->newPartecipantData)
            ->assertSessionHas(['status' => 'Iscrizione avvenuta con successo!']);

        $partecipant = Partecipant::where('phone', $this->newPartecipantData['phone'])->first();
        // Get the coupon
        $this->assertNull($partecipant->personalCoupon);
    }

    public function test_coupon_has_partecipant()
    {
        $this->assertInstanceOf(Partecipant::class, $this->coupon->partecipant);
    }

    /**
     * A coupon is valid only for a course
     *
     * @return void
     */
    public function test_coupon_is_linked_to_course()
    {
        $this->assertInstanceOf(Course::class, $this->coupon->course);
    }
}
