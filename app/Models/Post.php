<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'title',
        'body',
        'user_id',
    ];

    public function toSearchableArray() // used with Scout\Searchable
    {
        return ['title' => $this->title, 'body' => 'body'];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
