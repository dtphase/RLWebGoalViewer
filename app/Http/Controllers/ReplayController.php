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
}
