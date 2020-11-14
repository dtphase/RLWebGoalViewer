<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome;
use Tests\DuskTestCase;

class ReplayTest extends DuskTestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDownloadReplayById()
    {
        $replayId = "6c7b1dc3-176b-4d8a-a3e5-042055574a69";
        $this->browse(function ($browser) {
            $browser->visit(env('APP_URL', null) . '/api/replays/6c7b1dc3-176b-4d8a-a3e5-042055574a69/download')->assertSee('Success');
        });
    }
    
    public function testStoreReplay()
    {
        $replayId = "6c7b1dc3-176b-4d8a-a3e5-042055574a69";
        $this->browse(function ($browser) {
            $browser->visit(env('APP_URL', null) . '/api/replays/6c7b1dc3-176b-4d8a-a3e5-042055574a69/store')->assertSee('Success');
        });
    }
}
