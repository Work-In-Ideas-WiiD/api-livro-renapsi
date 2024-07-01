<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Notifications\NewPasswordNotification;
use App\Notifications\NewSupportNotification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Aluno\Acesso;
use App\Models\Aluno\Suporte;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, HasUuids, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'token',
        'email_verified_at',
        'is_admin',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function resetPasswordNotification(string $url): void
    {
        $this->notify(new NewPasswordNotification(
            token: $this->token,
            email: $this->email,
            url: $url,
            subject: 'Redefinição de senha - Livro',
            view: 'mail.resetar_senha'
        ));
    }

    public function sendNewPasswordRequestNotification(string $url): void
    {
        $this->notify(new NewPasswordNotification(
            token: $this->token,
            email: $this->email,
            url: $url,
            subject: 'Novo cadastro - Livro '
        ));
    }

    public function sendNewSendSuportRequestNotification(string $email, string $texto): void
    {
        $this->notify(new NewSupportNotification(
            email: $email,
            texto: $texto,
            subject: 'Email Suporte - Livro '
        ));
    }

    public function acesso(): HasMany
    {
        return $this->hasMany(Acesso::class);
    }

    public function suporte(): HasMany
    {
        return $this->hasMany(Suporte::class);
    }
}
