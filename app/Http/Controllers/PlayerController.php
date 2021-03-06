<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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

    public function displayPlayerList() {
        return '<a href="http://goalviewer.test/replays/76561198174027955">76561198174027955</a>';
    }

    public function displayPlayerReplayList($playerId) {
        $replays = $this->getReplaysBySteamId($playerId);
        $html = '<ul>';
        foreach($replays as $replay) {
            
            $html .= '<li><a href="http://goalviewer.test/replays/' . $replay['id'] . '/dsa' . '/' . $playerId . '">' .  $replay['id'] . '</a></li>';
        }
        return $html . '</ul>';
    }
}

//http://goalviewer.test/api/replays/76561198174027955
//http://goalviewer.test/api/replays/6c7b1dc3-176b-4d8a-a3e5-042055574a69/download