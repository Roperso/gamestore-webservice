<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // USER: beli game
    public function purchase($gameId)
    {
        $user = JWTAuth::user();

        if ($user->role !== 'user') {
            return response()->json([
                'message' => 'Only user can purchase game'
            ], 403);
        }

        $game = Game::find($gameId);
        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        // cek sudah beli atau belum
        if (Transaction::where('user_id', $user->id)->where('game_id', $gameId)->exists()) {
            return response()->json([
                'message' => 'Game already purchased'
            ], 409);
        }

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'game_id' => $gameId,
            'price' => $game->price,
            'purchased_at' => Carbon::now()
        ]);

        return response()->json([
            'message' => 'Purchase successful',
            'data' => $transaction
        ], 201);
    }

    // USER: lihat transaksi sendiri
    public function myTransactions()
    {
        $user = JWTAuth::user();

        return response()->json([
            'message' => 'My transactions',
            'data' => Transaction::with('game')
                ->where('user_id', $user->id)
                ->get()
        ]);
    }

    // ADMIN: lihat semua transaksi
    public function allTransactions()
    {
        $user = JWTAuth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'message' => 'All transactions',
            'data' => Transaction::with(['user', 'game'])->get()
        ]);
    }
}
