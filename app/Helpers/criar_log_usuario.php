<?php

use App\Models\Conteudo\Log as LogModel;

function criar_log_usuario($type, $model_nome = null, $model_id = null) {
    if ($model_nome and $model_id) {
        return LogModel::create([
            'user_id' => auth()->id(),
            'ip' => request()->server('HTTP_CF_CONNECTING_IP') ?? request()->ip(),
            'type' => $type,
            'model_nome' => $model_nome,
            'model_id' => $model_id,
        ]);
    }

    return LogModel::create([
        'user_id' => auth()->id(),
        'ip' => request()->server('HTTP_CF_CONNECTING_IP') ?? request()->ip(),
        'type' => $type,
    ]);
}
