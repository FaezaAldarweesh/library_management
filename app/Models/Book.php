<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'category_id',
        'description',
        'published_at',
        'status',
    ];

    public function category()
{
    return $this->belongsTo(Category::class);
}

    public function users()
    {
       return $this->hasMany(User::class);
    }

    public function BorrowRecords()
    {
       return $this->hasMany(BorrowRecord::class);
    }
    public function ratings()
    {
       return $this->hasMany(Rating::class);
    }
}
