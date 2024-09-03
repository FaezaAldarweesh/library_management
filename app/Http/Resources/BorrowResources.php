<?php

namespace App\Http\Resources;


use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
    
        return [
            'Borrow id'=> $this->id,
            'book name'=> $this->book->title,
            'user name'=> $this->user->name,
            'user email'=> $this->user->email,
            'borrow at'=> $this->borrow_at,
            'due at'=> $this->due_at,
            'returned at'=> $this->returned_at,
            'status'=>$this->status ?? 'It has not been borrowed yet',
        ];
    }
}
