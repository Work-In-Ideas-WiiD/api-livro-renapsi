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
        $pasta = '/home/charles/Downloads/livros/new/PROFISSIONALIZANTE TO/'; // NOME DA PASTA
        $pastas = scandir($pasta);

        foreach ($pastas as $item) {
            if ($item != '.' && $item != '..') {
                $caminho_completo = $pasta . $item;

                // Verifica se é um diretório
                if (is_dir($caminho_completo)) {
                    // Se for um diretório, escaneia o conteúdo
                    $files = scandir($caminho_completo);

                    // Ignora os diretórios '.' e '..'
                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..') {
                            $arquivo = $this->uploadToS3($caminho_completo . '/' . $file, $file);

                            if ($arquivo) {
                                $livro = Livro::updateOrCreate(
                                    [
                                        'titulo' => $item,
                                        'arquivo' => "livro/" . $file,
                                    ]
                                );

                                $livro->modulos()->syncWithoutDetaching(['9df57305-d27e-424a-859c-76d21034d2b7']);
                            }
                        }
                    }
                } else {
                    // Se for um arquivo, processa diretamente
                    $arquivo = $this->uploadToS3($caminho_completo, $item);

                    if ($arquivo) {
                        $livro = Livro::updateOrCreate(
                            [
                                'titulo' => pathinfo($item, PATHINFO_FILENAME), // Remove a extensão do nome do arquivo
                                'arquivo' => "livro/" . $item,
                            ]
                        );

                        $livro->modulos()->syncWithoutDetaching(['9df57305-d27e-424a-859c-76d21034d2b7']);
                    }
                }
            }
        }

        dd('FIM');
    }

    private function uploadToS3($file, $nome): bool|string
    {
        return Storage::disk(config('filesystems.default'))
            ->put("/livro/".$nome, file_get_contents($file));
    }
}
