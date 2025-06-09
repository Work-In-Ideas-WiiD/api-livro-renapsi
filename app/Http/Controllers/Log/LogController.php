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
        ->when($request->referencia, function ($query, $request) {
            $query->whereHas('model', function ($query) use ($request) {
                $query->where('arquivo', 'like', '%' . $request . '%')
                ->orWhere('nome', 'like', '%' . $request . '%');
            });
        })
        ->when($request->nome, function ($query, $request) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request . '%');
            });
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
        ->when($request->nome, function ($query, $request) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request . '%');
            });
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

    public function registroAcessoChart()
    {
        for ($i = 0; $i < 12; $i++) {

            $total = Acesso::whereMonth('created_at', $i +1)->count();

            $data[] = $total;
        }

        return response()->json($data, 200);
    }

    public function registroAcessoMesChart()
    {
        for ($i = 0; $i < 12; $i++) {

            $total = Acesso::whereMonth('created_at', $i +1)
            ->whereYear('created_at', date('Y'))
            ->count();

            $data[] = $total;
        }

        return response()->json($data, 200);
    }

    public function registroLivroMesChart()
    {
        for ($i = 0; $i < 12; $i++) {

            $total = Log::whereMonth('created_at', $i +1)
            ->whereYear('created_at', date('Y'))
            ->count();

            $data[] = $total;
        }

        return response()->json($data, 200);
    }

    public function registroLivroChart()
    {
        $cat = array("download", "online", "modulo");

        for ($i = 0; $i < 3; $i++) {

            $total = Log::whereType($cat[$i])->count();

            $data[] = $total;
        }

        return response()->json($data, 200);
    }
}
