<?php

namespace App\Services\Conteudos;

use App\Models\Conteudo\Modulo;
use App\Filters\Query\PostgreRelationFixer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ModuloService
{
    public const GLOBAL_FILTER_FIELDS = [
        'id',
        'nome',
        'descricao',
        'icone'
    ];

    public function index(Request $request): LengthAwarePaginator
    {
        $paginate = $request->get('per_page', 15);

        $query = QueryBuilder::for(Modulo::class)
            ->allowedFilters([
                AllowedFilter::custom('id', new PostgreRelationFixer()),
                'nome',
                // AllowedFilter::custom('global', new GlobalIndexFilter(self::GLOBAL_FILTER_FIELDS)),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([AllowedSort::field('criado_em', 'created_at'), 'nome'])
            ->allowedIncludes(['livros']);

        return $query->paginate($paginate);
    }

    public function store(Request $request): Modulo
    {
        $equipe_data = $request->safe()->all();
        $equipe_data['icone'] = $this->uploadToS3($request);

        return Modulo::create($equipe_data);
    }

    public function update(Request $request, Modulo $modulo): Modulo
    {
        $equipe_data = $request->safe()->except('icone');

        if(isset($request->safe()->icone) && $request->safe()->icone !== "null"){
            $equipe_data['icone'] = $this->uploadToS3($request);
        }

        return tap($modulo)->update($equipe_data);
    }

    private function uploadToS3(Request $request): bool|string
    {
        return Storage::disk(config('filesystems.default'))
            ->putFile("/icone", $request->file('icone'));
    }
}
