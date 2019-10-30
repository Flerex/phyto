<?php

namespace Tests;

use DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    /*
     * We set the RefreshDatabase trait in every test class because Spatie/laravel-permissions needs to
     * set every time the kernel is loaded all the permissions and the database cleanup seems to interfere
     * with this.
     */
    use RefreshDatabase;

    /**
     * Seeds database at the beginning of every test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);


    }
}
