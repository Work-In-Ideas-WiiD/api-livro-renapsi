<?php

namespace App\Http\Requests\Users;

use App\Enums\EstadoCivil;
use App\Enums\Sexo;
use App\Exceptions\SocialMedia\FacebookInvalidCredentialsException;
use App\Exceptions\SocialMedia\FacebookInvalidTokenException;
use App\Exceptions\SocialMedia\GoogleInvalidTokenException;
use App\Services\SocialMedia\SocialMediaService;
use Exception;
use Google_Client;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ClienteRegisterRequest extends FormRequest
{

    private SocialMediaService $socialMediaService;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->socialMediaService = new SocialMediaService();
    }

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user' => ['required', 'array'],
            'user.email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'piloto' => ['required', 'array'],
            'piloto.nome' => ['required', 'string'],
            'piloto.documento' => ['required', 'string', 'cpf'],
        ];
    }

    /**
     * @return void
     * @throws FacebookInvalidCredentialsException
     * @throws FacebookInvalidTokenException
     * @throws GoogleInvalidTokenException
     */
    protected function passedValidation(): void
    {
        $validated = $this->validated();

        if ($this->has('social_media.google_token_id')) {
            Arr::set(
                $validated,
                'social_media.google_token_id',
                $this->socialMediaService->verifyGoogleToken($this->input('social_media.google_token_id'))['sub']
            );
        }
        
        if ($this->has('social_media.facebook_token_id')) {
            Arr::set(
                $validated,
                'social_media.facebook_token_id',
                $this->socialMediaService->verifyFacebookToken($this->input('social_media.facebook_token_id'))->user_id
            );
        }

        $this->merge($validated);
    }
}
