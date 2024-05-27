<?php

namespace App\Http\Resources\Conteudo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ModuloCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => ModuloResources::collection($this->collection),
        ];
    }
}
