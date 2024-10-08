<?php

namespace App\Http\Resources;


use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
    
        return [
            'category id' => $this->id,
            'category name' => $this->name,
            //إعادة النتائج بناءاً على العلاقة المحملة سابقا مع استعلام جلب كل التصنيفات
            'books' => BookResources::collection($this->whenLoaded('books')),
        ];
    }
}
