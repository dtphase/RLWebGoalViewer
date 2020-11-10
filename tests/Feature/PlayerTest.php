<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlayerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetReplaysBySteamId()
    {
        $steamId = "76561198174027955";
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
