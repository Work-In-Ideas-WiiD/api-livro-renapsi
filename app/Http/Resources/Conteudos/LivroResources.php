<?php

namespace App\Http\Resources\Conteudos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LivroResources extends JsonResource
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
            'titulo' => $this->titulo,
            'arquivo' => $this->getImageUrl(),
            'criado_em' => $this->created_at->format('d/m/Y H:i:s'),
            'modulos' => $this->modulos,
            'tags' => $this->tags
        ];
    }

    private function getImageUrl(): string|null
    {
        if (Str::contains($this->arquivo, ['http', 'https', 'www'])) {
            return $this->arquivo;
        }

        return $this->arquivo !== null && Storage::disk('spaces')->exists($this->arquivo)
            ? Storage::disk('spaces')->temporaryUrl($this->arquivo, now()->addMinutes(120))
            : null;
    }
}
