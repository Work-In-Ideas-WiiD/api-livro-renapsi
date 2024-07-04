<?php

namespace App\Http\Controllers\Conteudo;

use App\Http\Controllers\BaseApiController;
use App\Models\Conteudo\Modulo;
use App\Http\Requests\Conteudo\Modulo\StoreRequest;
use App\Http\Requests\Conteudo\Modulo\UpdateRequest;
use App\Http\Resources\Conteudos\ModuloCollection;
use App\Http\Resources\Conteudos\ModuloResources;
use App\Services\Conteudos\ModuloService;
use App\Http\Requests\Global\GlobalIndexRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ModuloController extends BaseApiController
{
    public function __construct(private readonly ModuloService $moduloService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GlobalIndexRequest $request): ModuloCollection
    {
        return new ModuloCollection($this->moduloService->index($request));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): ModuloResources
    {
        return $this->show($this->moduloService->store($request));
    }

   /**
     * Display the specified resource.
     */
    public function show(Modulo $modulo): ModuloResources
    {
        return new ModuloResources($modulo);
    }

     /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Modulo $modulo): ModuloResources
    {
        return $this->show($this->moduloService->update($request, $modulo));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modulo $modulo): JsonResponse
    {
        $modulo->delete();
        return $this->sendResponse('', true, Response::HTTP_NO_CONTENT);
    }

    public function lista()
    {
        $modulos = Modulo::get(['nome', 'id']);
        return response()->json($modulos, 200);
    }
}
