<?php

namespace App\Http\Requests\Conteudo\Modulo;

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
        if($this->input('icone') === "null")
            $icone = 'nullable';
        else
            $icone = 'nullable|mimes:jpeg,jpg,png,gif|max:10000';

        return [
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'icone' => $icone,
        ];
    }
}
