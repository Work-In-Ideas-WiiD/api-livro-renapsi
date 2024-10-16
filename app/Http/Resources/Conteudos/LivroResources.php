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
            'tags' => $this->tags,
            'nome_modulos' => $this->modulos->pluck('nome')->implode(' , '),
            'nome_tags' => $this->tags->pluck('nome')->implode(' , ')
        ];
    }

    private function getImageUrl(): string|null
    {
        if (Str::contains($this->arquivo, ['http', 'https', 'www'])) {
            return $this->arquivo;
        }

        return $this->arquivo !== null && Storage::disk('s3')->exists($this->arquivo)
            ? Storage::disk('s3')->temporaryUrl($this->arquivo, now()->addMinutes(120))
            : null;
    }
}
