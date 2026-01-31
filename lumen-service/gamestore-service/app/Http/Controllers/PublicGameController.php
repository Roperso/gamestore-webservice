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
        $games = Game::with(['category', 'developer', 'images'])
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
        $game = Game::with(['category', 'developer', 'images'])->find($id);

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

    public function xml()
    {
        $games = Game::with(['category', 'developer'])->get();

        $xml = new \SimpleXMLElement('<games/>');

        foreach ($games as $game) {
            $gameXml = $xml->addChild('game');
            $gameXml->addChild('id', $game->id);
            $gameXml->addChild('title', $game->title);
            $gameXml->addChild('price', $game->price);
            $gameXml->addChild('category', $game->category->name);
            $gameXml->addChild('developer', $game->developer->name);
        }

        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml');
    }

}
