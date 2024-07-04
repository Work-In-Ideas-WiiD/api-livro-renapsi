<?php

namespace App\Services\Conteudos;

use App\Actions\Images\UploadImage;
use App\Actions\Tags\CreateTags;
use App\Filters\Monitoramentos\MonitoramentoRelationFilter;
use App\Filters\Atividade\PostgreRelationFixer;
use App\Filters\Global\GlobalIndexFilter;
use App\Models\Conteudo\Livro;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Storage;

class LivroService
{

    public const GLOBAL_FILTER_FIELDS = [
        'id',
        'arquivo'
    ];

    public function index(Request $request): LengthAwarePaginator
    {
        $paginate = $request->get('per_page', 15);
        $query = QueryBuilder::for(Livro::class)
            ->allowedFilters([
                AllowedFilter::custom('id', new PostgreRelationFixer()),
                'arquivo',
                AllowedFilter::custom('tags_nome', new MonitoramentoRelationFilter()),
                AllowedFilter::callback('search', function($query, $value){
                    $query->where('titulo', 'like', '%' . $value . '%')
                    ->orWhereHas('modulos', function($query) use ($value) {
                        $query->where('nome', 'like', '%' . $value . '%');
                    })
                    ->orWhereHas('tags', function($query) use ($value) {
                        $query->where('nome', 'like', '%' . $value . '%');
                    });
                }),
            ])
            ->allowedIncludes(['modulos', 'tags'])
            ->defaultSort('-created_at')
            ->allowedSorts([AllowedSort::field('criado_em', 'created_at')]);

        return $query->paginate($paginate);
    }

    public function store(Request $request): Livro
    {
        return DB::transaction(function () use ($request) {
            $livro_data = $request->safe()->only('arquivo', 'titulo');
            $livro_data['arquivo'] = $this->uploadToS3($request);

            $livro = Livro::create($livro_data);

            if($request->safe()->tags)
                $livro->tags()->sync((new CreateTags())->handle($request->safe()->tags));

            $livro->modulos()->sync($request->safe()->modulos);

            $livro->load(['modulos', 'tags']);
            return $livro;
        });
    }

    public function update(Request $request, Livro $livro): Livro
    {
        return DB::transaction(function () use ($request, $livro) {
            $livro_data = $request->safe()->only('arquivo', 'titulo');

            if ($request->hasFile('arquivo')) {
                $livro_data['arquivo'] = $this->uploadToS3($request);
            }

            $livro = tap($livro)->update($livro_data);
            $livro->tags()->sync((new CreateTags())->handle($request->safe()->tags));
            $livro->modulos()->sync($request->safe()->modulos);

            return $livro;
        });
    }

    private function uploadToS3(Request $request): bool|string
    {
        return Storage::disk('spaces')
            ->putFile("/livro", $request->file('arquivo'));
    }
}
