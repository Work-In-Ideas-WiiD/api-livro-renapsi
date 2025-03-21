<?php

namespace App\Http\Resources\Conteudos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ModuloIndexResources extends JsonResource
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
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'icone' => $this->getImageUrl(),
            'criado_em' => $this->created_at->format('d/m/Y H:i:s')
        ];
    }

    private function getImageUrl(): string|null
    {
        if (Str::contains($this->icone, ['http', 'https', 'www'])) {
            return $this->icone;
        }

        return $this->icone !== null && Storage::disk(config('filesystems.default'))->exists($this->icone)
            ? Storage::disk(config('filesystems.default'))->temporaryUrl($this->icone, now()->addMinutes(120))
            : null;
    }
}
