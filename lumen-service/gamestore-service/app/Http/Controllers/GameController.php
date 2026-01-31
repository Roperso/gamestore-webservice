<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class GameController extends Controller
{
    public function index()
    {
        return response()->json(
            Game::with('category', 'developer')->get()
        );
    }

    public function show($id)
    {
        $game = Game::with('category', 'developer')->find($id);

        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        return response()->json($game);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:3',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $user = JWTAuth::user();

        if (!in_array($user->role, ['admin', 'developer'])) {
            return response()->json([
                'message' => 'Only admin or developer can create game'
            ], 403);
        }

        $game = Game::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'developer_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Game created',
            'data' => $game
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $game = Game::find($id);
        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        $user = JWTAuth::user();

        if ($user->role !== 'admin' && $game->developer_id !== $user->id) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        $game->update($request->only(['title', 'description', 'price', 'category_id']));

        return response()->json([
            'message' => 'Game updated',
            'data' => $game
        ]);
    }

    public function destroy($id)
    {
        $game = Game::find($id);
        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        $user = JWTAuth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Only admin can delete game'
            ], 403);
        }

        $game->delete();

        return response()->json(['message' => 'Game deleted']);
    }
}
