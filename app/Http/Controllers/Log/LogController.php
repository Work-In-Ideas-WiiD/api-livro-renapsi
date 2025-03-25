<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use App\Models\Conteudo\Log;
use Illuminate\Http\Request;
use App\Models\Aluno\Acesso;
use Str;


class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $order = ($request->order) ? $request->input('order') : 'DESC';
        $mostrar = ($request->mostrar) ? $request->input('mostrar') : 10;

        return Log::with('user', 'model')
        ->when($request->type, function($query, $value) {
            $query->where('type', $value);
        })
        ->when($request->user_id, function($query, $value) {
            $query->where('user_id', $value);
        })
        ->when($request->data_inicial, function ($query) use ($request) {
            $query->whereDate('created_at', '>=', $request->data_inicial);
        })
        ->when($request->data_final, function ($query) use ($request) {
            $query->whereDate('created_at', '<=', $request->data_final);
        })
        ->orderBy('created_at', $order)->paginate($mostrar);
    }

    /**
     * Display a listing of the resource.
     */
    public function indexAcesso(Request $request)
    {
        $order = ($request->order) ? $request->input('order') : 'DESC';
        $mostrar = ($request->mostrar) ? $request->input('mostrar') : 10;

        return Acesso::with('user')
        ->when($request->user_id, function($query, $value) {
            $query->where('user_id', $value);
        })
        ->when($request->data_inicial, function ($query) use ($request) {
            $query->whereDate('created_at', '>=', $request->data_inicial);
        })
        ->when($request->data_final, function ($query) use ($request) {
            $query->whereDate('created_at', '<=', $request->data_final);
        })
        ->orderBy('created_at', $order)->paginate($mostrar);
    }

    public function show($log)
    {
        if (!Str::isUuid($log)) {
            return abort(404);
        }

        return Log::find($log);
    }

}
