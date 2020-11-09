<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReplayTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAllTests()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertTrue(true);
    }

}
