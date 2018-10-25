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

    protected function setUp()
    {
        parent::setUp();
        $this->user = factory('App\User')->create();

        factory('App\Course', 10)->create();
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
            'phone' => '3' . rand(111111111, 999999999),
            'job' => $data['job'],
            'city' => $data['city'],
            'coupon' => strtoupper('coupon' . random_int(111, 999)),
            'course_id' => Course::inRandomOrder()->first()->id,
            'meta' => json_encode(['ip' => '127.0.0.2']),
        ];

        $this->newNewsletterData = [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'region_id' => Region::inRandomOrder()->first()->id,
            'active' => 1,
            'meta' => json_encode(['ip' => '127.0.0.2']),
        ];
    }

    /**
     * Test the Partecipant method to check a user without a coupon
     *
     * @return void
     */
    public function test_user_doesnt_have_coupon()
    {
        Queue::fake();
        // Create a user without a coupon
        unset($this->newPartecipantData['coupon']);
        $res = $this->post(route('partecipant.store'), $this->newPartecipantData)
            ->assertSessionHas(['status' => 'Iscrizione avvenuta con successo!']);
        $newPartecipant = Partecipant::where('phone', $this->newPartecipantData['phone'])->first();
        $this->assertFalse($newPartecipant->hasCoupon());
        $this->assertNull($newPartecipant->getCoupon());
    }

    /**
     * Check that a coupon is valid
     *
     * @return void
     */
    public function test_valid_coupon_check()
    {
        // Valid coupon
        $coupon = Coupon::create(['value' => 'ValidCoupon', 'active' => true]);
        $data = ['coupon' => $coupon->value];
        $res = $this->get(route('coupon.check', $data));
        $this->assertEquals('ok', json_decode($res->getContent())->status);
    }

    /**
     * Test a check with an invalid coupon returns ko
     *
     * @return void
     */
    public function test_invalid_coupon_check()
    {
        // Inactive coupon
        $coupon = Coupon::create(['value' => 'ValidCoupon', 'active' => false]);
        $data = ['coupon' => $coupon->value];
        $res = $this->get(route('coupon.check', $data));
        $this->assertEquals('ko', json_decode($res->getContent())->status);
    }

    /**
     * Test a check with an inactive coupon returns ko
     *
     * @return void
     */
    public function test_inactive_coupon_check()
    {
        // Invalid coupon
        $data = ['coupon' => 'InvalidCoupon'];
        $res = $this->get(route('coupon.check', $data));
        // dd($res->getContent());
        $this->assertEquals('ko', json_decode($res->getContent())->status);
    }

    /**
     * Test coupons are checked before subscribing user: valid
     *
     * @return void
     */
    public function test_check_valid_coupon_on_subscription()
    {
        Queue::fake();
        $coupon = Coupon::create(['value' => strtoupper(str_random(5)), 'active' => true]);
        $this->newPartecipantData['coupon'] = $coupon->value;

        $res = $this->post(route('partecipant.store'), $this->newPartecipantData)
            ->assertSessionHas(['status' => 'Iscrizione avvenuta con successo!']);

        // Assert the coupons are equals
        $newPartecipant = Partecipant::where('phone', $this->newPartecipantData['phone'])->first();
        $this->assertEquals(strtoupper($coupon->value), strtoupper($newPartecipant->getCoupon()));
    }

    /**
     * Test coupons are checked before subscribing user: invalid
     *
     * @return void
     */
    public function test_check_invalid_coupon_on_subscription()
    {
        Queue::fake();
        $this->newPartecipantData['coupon'] = strtoupper(str_random(5));

        $res = $this->post(route('partecipant.store'), $this->newPartecipantData)
            ->assertSessionHas(['status' => 'Iscrizione avvenuta con successo!']);

        // Assert the coupons are equals
        $newPartecipant = Partecipant::where('phone', $this->newPartecipantData['phone'])->first();
        $this->assertNull($newPartecipant->getCoupon());
    }
}
