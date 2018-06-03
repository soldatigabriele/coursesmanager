<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    
    // protected $afterApplicationCreatedCallbacks;
    

    protected function setUp(){
    	parent::setUp();
		$this->artisan('db:seed', ['--class' => 'RegionsTableSeeder']);
    }
}
