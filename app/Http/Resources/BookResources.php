<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'book id' => $this->id,
            'book title' => $this->title,
            'book author' => $this->author,
            'book category' => $this->category->name, 
            'book description' => $this->description,
            'book published_at' => $this->published_at,
            'book status' => $this->status ?? 'available',
            //بحال كان الكتاب لا يحوي على تفييمات سيرد قيمة 0
            'book avareg rating' => $this->ratings->avg('rating') ?? 0,
            'ratings book' => RatingResources::collection($this->whenLoaded('ratings')),
        ];
    }
}
