<?php

namespace App\Models\Conteudo;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class LivroTag extends Pivot
{
    use HasUuids, SoftDeletes;
}
