<?php

namespace App\Actions\Images;

use App\Models\Kartodromo\Kartodromo;
use App\Models\Kartodromo\Tracado;
use App\Models\Kartodromo\Imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadImage
{

    /**
     * @param Request $request
     * @param Conteudo|Faq $model
     * @param string $path
     * @return Imagem|bool
     */
    public static function handle(Request $request, Kartodromo|Tracado $model, string $path): Imagen|array
    {
        $image_content = $request->safe()->imagen;

        $image_content['arquivo'] = Storage::disk('s3')
            ->putFile("/$path", $request->file('imagen.arquivo'));


        return $model->imagens()->create($image_content);
    }

    public static function removeImage(string $path): void
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
