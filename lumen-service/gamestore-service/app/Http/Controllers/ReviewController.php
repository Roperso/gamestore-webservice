<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Review;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReviewController extends Controller
{
    // PUBLIC: list review per game
    public function index($gameId)
    {
        $game = Game::find($gameId);
        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        $reviews = Review::with('user')
            ->where('game_id', $gameId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Game reviews',
            'data' => $reviews
        ]);
    }

    // LOGIN: create review
    public function store(Request $request, $gameId)
    {
        $user = JWTAuth::user();
        $game = Game::find($gameId);

        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        $this->validate($request, [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        // cegah double review
        if (Review::where('game_id', $gameId)->where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'You already reviewed this game'
            ], 409);
        }

        $review = Review::create([
            'game_id' => $gameId,
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'message' => 'Review submitted',
            'data' => $review
        ], 201);
    }

    // LOGIN: delete review (owner/admin)
    public function destroy($id)
    {
        $user = JWTAuth::user();
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        if ($user->role !== 'admin' && $review->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted']);
    }
}
