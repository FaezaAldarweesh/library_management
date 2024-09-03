<?php

namespace App\Models;

use Illuminate\Auth\Events\Failed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'book_id',
        'rating',
        'review',
    ];

    public function book()
    {
       return $this->belongsTo(book::class);
    }

    public function user()
    {
       return $this->belongsTo(user::class);
    }
}
