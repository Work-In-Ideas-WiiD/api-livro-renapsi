<?php

namespace App\Filters\Monitoramentos;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\Filters\Filter;

class MonitoramentoRelationFilter implements Filter
{

    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->whereHas($this->getRelationAsString($property), function (Builder $query) use ($value) {
            $query->where('nome', 'ilike', '%' . $value . '%');
        });
    }

    private function getRelationAsString(string $field): string
    {
        return Str::of($field)
            ->beforeLast('_')
            ->replace('_', '.')
            ->value();
    }
}
