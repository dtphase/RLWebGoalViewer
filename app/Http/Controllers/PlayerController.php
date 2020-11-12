<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayerController extends Controller
{
    //Returns list of replay IDs
    public function getReplaysBySteamId($steamId) {
        $endpoint = "https://ballchasing.com/api/replays";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $endpoint, 
        
            [
                'headers' => [
                    'Authorization' => env('BALLCHASING_TOKEN'),
                ],
                'query' => [
                    "player-id" => "steam:" . $steamId,
                    "count" => 200
                ]
            ]
        );

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        $data = $content = json_decode($response->getBody(), true);

        return $data['list'];
    }
}

//http://goalviewer.test/replays/76561198174027955