<?php

namespace App\Http\Controllers;

use App\Models\Game;

class PublicGameController extends Controller
{
    /**
     * Menampilkan semua game (PUBLIC)
     * Bisa diakses tanpa login
     */
    public function index()
    {
        $games = Game::with(['category', 'developer'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Public game list',
            'data' => $games
        ], 200);
    }

    /**
     * Menampilkan detail game berdasarkan ID (PUBLIC)
     * Bisa diakses tanpa login
     */
    public function show($id)
    {
        $game = Game::with(['category', 'developer'])->find($id);

        if (!$game) {
            return response()->json([
                'message' => 'Game not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Game detail',
            'data' => $game
        ], 200);
    }
}
