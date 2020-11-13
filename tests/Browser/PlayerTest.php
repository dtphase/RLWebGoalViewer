<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome;
use Tests\DuskTestCase;


class PlayerTest extends DuskTestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //Expecting list of 200 replays
    public function testGetReplaysBySteamId()
    {
        $steamId = "76561198174027955";
        $this->browse(function ($browser) {
            $browser->visit(env('APP_URL', null) . '/api/replays/76561198174027955')->assertSee('steam_id');
        });
    }

    public function testDownloadReplayById()
    {
        $replayId = "6c7b1dc3-176b-4d8a-a3e5-042055574a69";
        $this->browse(function ($browser) {
            $browser->visit(env('APP_URL', null) . '/api/replays/6c7b1dc3-176b-4d8a-a3e5-042055574a69/download')->assertSee('Success');
        });
    }
}
