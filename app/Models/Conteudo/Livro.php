<?php

namespace App\Models\Conteudo;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Livro extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function modulos(): BelongsToMany
    {
        return $this->belongsToMany(Modulo::class, 'livro_modulos')->using(LivroModulo::class)->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'livro_tags')->using(LivroTag::class)->withTimestamps();
    }
}
