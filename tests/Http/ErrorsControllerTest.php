<?php

namespace Tests\Http;

use Tests\TestCase;
use App\ApplicationLog;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ErrorsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test logs are correctly retrieved
     *
     * @return void
     */
    public function test_errors_index()
    {
        // Create a log
        $this->actingAs(factory('App\User')->create());
        for ($i = 0; $i < 5; $i++) {
            ApplicationLog::create([
                'status' => 0,
                'description' => $this->faker->sentence,
                'value' => [
                    'one' => 'value one',
                    'two' => 'value two',
                    'three' => 'value three',
                    'errors' => [
                        'one' => ['value one is required'],
                        'two' => ['value two is required'],
                        'three' => ['value three is required'],
                    ]],
                'meta' => 'metadata',
            ]);
        }

        // Show the log index page
        $response = $this->get(route('errors.index'))->assertStatus(200);
        $this->assertEquals(5, ($response->original->getData()['errors'])->count());
    }

    /**
     * Test a log is correctly retrieved and shown
     *
     * @return void
     */
    public function test_errors_show()
    {
        // Create a log
        $this->actingAs(factory('App\User')->create());
        $log = ApplicationLog::create([
            'status' => 0,
            'description' => $this->faker->sentence,
            'value' => [
                'one' => 'value one',
                'two' => 'value two',
                'three' => 'value three',
                'errors' => [
                    'one' => ['value one is required'],
                    'two' => ['value two is required'],
                    'three' => ['value three is required'],
                ]],
            'meta' => 'metadata',
        ]);

        // Show the log show page
        $response = $this->get(route('errors.show', $log));
        $this->assertEquals(($response->original->getData()['error'])->toArray(), $log->toArray());
    }
}
