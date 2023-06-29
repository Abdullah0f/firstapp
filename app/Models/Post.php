<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    // add fillable property
    protected $fillable = [
        'title',
        'body',
        'user_id'
    ];

    function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
    }
}
