<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Pilotos\UsuarioResources;
use App\Http\Resources\Conteudos\AvaliacaoResources;
use App\Http\Resources\SocialMedias\SocialMediaResources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
{
    public static $wrap = '';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'admin' => $this->is_admin,
            'criado_em' => $this->created_at->format('d/m/Y H:i:s'),
            'email_verificado_em' => $this->email_verified_at?->format('d/m/Y H:i:s')
        ];
    }
}
