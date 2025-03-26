<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->when($this->description !== null, $this->description),
            'price' => $this->price,
            'vatRate' => $this->vat_rate,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'tag' => $this->when($this->tag_id !== null, new TagResource($this->whenLoaded('tag')))
        ];
    }
}
