<?php

namespace App\Filters\Query;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class PostgreRelationFixer implements Filter
{
    /**
     * @param Builder $query
     * @param $value
     * @param string $property
     * @return void
     */
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->where($property, $value);
    }
}
