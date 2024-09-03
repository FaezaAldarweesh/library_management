<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BorrowRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'borrow_at',
        'due_at',
        'returned_at',
        'status'
    ];

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($borrow) {
    //         $borrow->user_id = Auth::user()->id;
    //     });
    // }

    public function book()
    {
       return $this->belongsTo(Book::class);
    }

    public function user()
    {
       return $this->belongsTo(User::class);
    }
}
