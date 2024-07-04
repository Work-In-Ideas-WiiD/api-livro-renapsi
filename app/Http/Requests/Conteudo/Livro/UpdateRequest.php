<?php

namespace App\Http\Requests\Conteudo\Livro;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->input('livro.arquivo') === "null")
            $arquivo = 'nullable';
        else
            $arquivo = 'nullable|mimes:pdf|max:10000';

        return [
            'livro' => ['required', 'array'],
            'livro.arquivo' => $arquivo,
            'livro.titulo' => ['required', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string'],
            'modulos' => ['required', 'array'],
            'modulos.*' => ['required', 'uuid']
        ];
    }
}
