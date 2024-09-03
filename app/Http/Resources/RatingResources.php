<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'user name' => $this->user->name,
            'book name' => $this->book->title,
            'rating' => $this->rating,
            'review' => $this->review,
        ];
    }
}
