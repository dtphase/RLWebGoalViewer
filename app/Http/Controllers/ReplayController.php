<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Replay;
use App\Http\Controllers\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ReplayController extends Controller
{
    public function downloadReplayById($replayId) {
        $endpoint = "https://ballchasing.com/api/replays/";
        //$toFile = 'C:\laragon\www\goalviewer\storage\temp.replay';
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('GET', $endpoint . $replayId . '/file', 
        [
            'headers' => [
                'Authorization' => env('BALLCHASING_TOKEN'),
            ]
        ]);
        $body = $response->getBody();
        $data = $body->getContents();
        \Storage::put('temp.replay', $data);
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
        \Storage::move("temp.replay", 'replays/' . $replayId . '/' . $replayId . '.replay');
        return 'Success';
    }

    public function analyzeReplay($replayId) {
        $replay = Replay::where('replay_id', $replayId)->first();
        

        $process = new Process(['python', \Storage::path('analyzeReplay.py'), $replayId]);// -i ' . \Storage::path('replays/' . $replay["replay_id"]. '.replay') . '--json analysis.json --proto analysis.pts --gzip frames.gzip']);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();
    }

    public function getActiveReplay($playerId) {
        $replay = Replay::where('status', 'NEW')->first();
        echo json_encode([$replay->replay_id, $playerId]);
    }

    public function nextActiveReplay($playerId) {
        $replay = Replay::where('status', 'NEW')->first();
        $next = Replay::where('id', $replay->id + 1)->first();
        echo json_encode([$next->replay_id, $playerId]);
    }
}


//http://localhost:4000/?replay_id=6c7b1dc3-176b-4d8a-a3e5-042055574a69&player_id=76561198174027955&goals=1234,1235,1299