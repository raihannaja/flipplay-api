<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'genre' => $this->genre,
            'year' => $this->year,
            'rating' => $this->rating,
            'episodes' => $this->when($this->type === 'Drama', $this->episodes),
            'duration' => $this->when($this->type === 'Film', $this->duration),
            'users' => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
