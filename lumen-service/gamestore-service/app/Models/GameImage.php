<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameImage extends Model
{
    protected $fillable = ['game_id', 'image'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
