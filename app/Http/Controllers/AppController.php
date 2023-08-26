<?php

namespace App\Http\Controllers;

use App\Services\PlayerService;
use Illuminate\Http\Request;

class AppController extends Controller
{
    private $playerService;
    /**
     * Summary of __construct
     * @param \App\Services\PlayerService $playerService
     */
    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }
    public function addPlayer(Request $req)
    {
        return $this->playerService->addPlayer($req->validate(['slug' => 'required|string']));
    }

    public function searchPlayer($keyword)
    {
        return $this->playerService->searchPlayer($keyword);
    }

    public function notifyPlayer(Request $req)
    {
        $request = $req->validate(['player_id' => 'required', 'slug' => 'required']);
        return $this->playerService->notifyPlayer($request['player_id'], $request['slug']);
    }

}
