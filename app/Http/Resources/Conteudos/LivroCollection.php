<?php

namespace App\Http\Resources\Conteudos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LivroCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => LivroResources::collection($this->collection),
        ];
    }
}
