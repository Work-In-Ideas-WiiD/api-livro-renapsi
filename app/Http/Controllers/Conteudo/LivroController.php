<?php

namespace App\Http\Controllers\Conteudo;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Conteudo\Livro;
use App\Http\Requests\Conteudo\Livro\StoreRequest;
use App\Http\Requests\Conteudo\Livro\UpdateRequest;
use App\Http\Resources\Conteudos\LivroCollection;
use App\Http\Resources\Conteudos\LivroResources;
use App\Services\Conteudos\LivroService;
use App\Http\Requests\Global\GlobalIndexRequest;
use App\Models\Conteudo\Modulo;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class LivroController extends BaseApiController
{
    public function __construct(private readonly LivroService $livroService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GlobalIndexRequest $request): LivroCollection
    {
        return new LivroCollection($this->livroService->index($request));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): LivroResources
    {
        return $this->show($this->livroService->store($request));
    }

   /**
     * Display the specified resource.
     */
    public function show(Livro $livro): LivroResources
    {
        criar_log_usuario(
            'online',
            Livro::class,
            $livro->id
        );

        return new LivroResources($livro);
    }

     /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Livro $livro): LivroResources
    {
        return $this->show($this->livroService->update($request, $livro));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livro $livro): JsonResponse
    {
        $livro->delete();
        return $this->sendResponse('', true, Response::HTTP_NO_CONTENT);
    }

     /**
     * Lista de tags.
     *
     * @return void
     */

    public function listTags(Livro $livro): JsonResponse
    {
        $tags = $livro->tags;
        $palavras = [];
        foreach($tags as $tag){
            $key = [
                'key' => $tag->id,
                'value' => $tag->nome,
            ];

            array_push($palavras, $key);
        }

        return response()->json($palavras, 200);
    }

    public function total()
    {
        $total = [];
        $total['alunos'] = User::where('is_admin', 0)->where('role', 0)->count();
        $total['modulos'] = Modulo::count();
        $total['livros'] = Livro::count();

        return response()->json($total, 200);

    }

    public function registarDownload(Livro $livro): JsonResponse
    {
        criar_log_usuario(
            'download',
            Livro::class,
            $livro->id
        );

        return response()->json(['sucess' => 'Log download registrado'], 200);
    }
}
