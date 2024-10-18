<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Conteudo\Livro;

class SendLivrosStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-livros-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pasta = '/home/charles/Downloads/livros/bilbioteca/6. Módulo Profissionalizante/'; //NOME DA PASTA
        $pastas = scandir($pasta);

        foreach($pastas as $pasta_doc)
        {
            if($pasta_doc != '.' && $pasta_doc != '..')
            {
                $files = scandir($pasta.$pasta_doc);
                // dd($pasta_doc, $files[2], $pasta.$pasta_doc.'/'.$files[2]);

                if(isset($files[2]))
                {
                    $arquivo = $this->uploadToS3($pasta.$pasta_doc.'/'.$files[2], $files[2]);

                    if($arquivo)
                    {
                        $livro = Livro::updateOrCreate(
                            [
                                'titulo' => $pasta_doc,
                                'arquivo' => "livro/".$files[2],
                            ]
                        );

                        $livro->modulos()->sync(['9d46c33b-76d6-43fa-914b-1a048f0adf21']);

                    }
                }
            }
        }

        dd('FIM');
    }

    private function uploadToS3($file, $nome): bool|string
    {
        return Storage::disk('s3')
            ->put("/livro/".$nome, file_get_contents($file));
    }
}
