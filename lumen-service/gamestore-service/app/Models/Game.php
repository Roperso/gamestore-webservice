<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'category_id', 'developer_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}
