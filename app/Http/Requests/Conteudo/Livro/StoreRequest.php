<?php

namespace App\Http\Requests\Conteudo\Livro;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        return [
            'livro' => ['required', 'array'],
            'livro.arquivo' => ['required', 'file', 'mimes:png,jpg,jpeg,bmp'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string'],
            'modulos' => ['required', 'array'],
            'modulos.*' => ['required', 'uuid']
        ];
    }
}