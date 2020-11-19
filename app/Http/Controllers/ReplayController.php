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
        \Storage::move("temp.replay", 'replays/' . $replayId . '/' . $replayId . '.replay'); //temp
        return 'Success';
    }

    public function analyzeReplay($replayId, $playerId) {
        $replay = Replay::where('replay_id', $replayId)->first();
        

        $process = new Process(['python', \Storage::path('analyzeReplay.py'), $replayId, $playerId]);// -i ' . \Storage::path('replays/' . $replay["replay_id"]. '.replay') . '--json analysis.json --proto analysis.pts --gzip frames.gzip']);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $replay->goals = $process->getOutput();
        $replay->save();
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

    public function downloadStoreAnalyze($replayId, $playerId) {
        $this->downloadReplayById($replayId);
        $this->storeReplay($replayId);
        $this->analyzeReplay($replayId, $playerId);
    }
    
    public function displayViewerUrl($replayId, $playerId) {
        $replay = Replay::where('replay_id', $replayId)->first();
        return 'http://localhost:4000?replay_id=' . $replayId . '&player_id=' . $playerId . '&goals=' . $replay->goals;
    }
    
    public function displayViewerLinkList($playerId) {
        $html = '';
        $replays = Replay::where('player_id', $playerId)->take(10);
        foreach($replays as $replay) {
            $html .= $this->displayViewerUrl($replay->replay_id, $playerId);
        }
        return $html;
    }
}

    


//http://localhost:4000/?replay_id=b2170769-3858-4cd3-a258-3ed0e034777e&player_id=76561198174027955&goals=17169,25047
