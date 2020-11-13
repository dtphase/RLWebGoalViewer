<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Replay;

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

    public function downloadReplayById($replayId) {
        $endpoint = "https://ballchasing.com/api/replays/";
        $toFile = 'C:\laragon\www\goalviewer\storage\temp.replay';
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('GET', $endpoint . $replayId . '/file', 
        [
            'headers' => [
                'Authorization' => env('BALLCHASING_TOKEN'),
            ]
        ]);
        $body = $response->getBody();
        $data = $body->getContents();
        Storage::put('temp.replay', $data);
        // $replayFile = fopen($toFile, "w");
        // fwrite($replayFile, $data);
        // fclose($replayFile);
        return 'Success';
        //$resource = fopen('C:\laragon\www\goalviewer\storage\temp.replay', 'w');
        //$stream = GuzzleHttp\Psr7\stream_for($resource);
    }

    //Meant to take a temporarily stored replay and move it to permanent storage and create a new model in the database
    public function storeReplay($replayId)  {
        //TODO: Check if file exists
        $replay = new Replay;
        $replay->replay_id = $replayId;
        $replay->status = 'NEW';
        $replay->save();
        Storage::move("temp.replay", 'replays/' . $replayId . '.replay');
    }
}

//http://goalviewer.test/api/replays/76561198174027955
//http://goalviewer.test/api/replays/6c7b1dc3-176b-4d8a-a3e5-042055574a69/download