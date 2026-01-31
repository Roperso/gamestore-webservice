<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameImage;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

if (!function_exists('public_path')) {
    function public_path($path = '') {
        return app()->basePath('public') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

class GameImageController extends Controller
{
    public function upload(Request $request, $gameId)
    {
        $user = JWTAuth::user();
        $game = Game::find($gameId);

        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        if ($user->role !== 'admin' && $game->developer_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();

        $destinationPath = public_path('game_images');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $file->move($destinationPath, $filename);

        $image = GameImage::create([
            'game_id' => $game->id,
            'image' => $filename
        ]);

        return response()->json([
            'message' => 'Image uploaded',
            'data' => $image
        ], 201);
    }


    public function show($filename)
    {
        $path = public_path('game_images/'.$filename);

        if (!file_exists($path)) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        return response()->file($path);
    }
}
