<?php

namespace App\Services\SocialMedia;

use App\Exceptions\SocialMedia\FacebookInvalidCredentialsException;
use App\Exceptions\SocialMedia\FacebookInvalidTokenException;
use App\Exceptions\SocialMedia\GoogleInvalidTokenException;
use App\Models\SocialMedia;
use App\Models\User;
use Exception;
use Google_Client;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SocialMediaService
{

    public function createOrUpdateSocialMedias(Authenticatable|User $user, array $socialMedia): Model|SocialMedia
    {
        return $user->socialMedia()->updateOrCreate(['user_id' => $user->id], $socialMedia);
    }

    public function getSocialMedia(string $field, string $token): Builder|Model|null
    {
        return SocialMedia::withWhereHas('user', function ($query) {
            $query->whereNotNull('email_verified_at');
        })->where($field, $token)
            ->first();
    }

    public function isFacebookTokenValid(string $token): object|bool
    {
        try {
            return $this->verifyFacebookToken($token);
        } catch (FacebookInvalidTokenException|FacebookInvalidCredentialsException) {
            return false;
        }
    }

    public function isGoogleTokenValid(string $token): bool|array
    {
        try {
            return $this->verifyGoogleToken($token);
        } catch (GoogleInvalidTokenException) {
            return false;
        }
    }

    public function isEmailAvailable(string $email): null|User
    {
        return User::firstWhere('email', $email);
    }

    public function isAuthenticatedUserEmailDifferentFromRequest(string $request_email): bool
    {
        if (!($user = User::firstWhere('email', $request_email))) {
            return false;
        }
        return $user->email !== Auth::user()->email;
    }

    public function isMoodleValid(string $username, string $password): object|bool
    {
        try {
            return $this->verifyMoodle($username, $password);
        } catch (FacebookInvalidTokenException|FacebookInvalidCredentialsException) {
            return false;
        }
    }


    /**
     * @throws FacebookInvalidCredentialsException
     */
    protected function authenticateOnFacebook(): object|bool
    {
        $response = Http::acceptJson()->get(
            config('social_media.facebook.auth_url'),
            collect(config('social_media.facebook'))
                ->only('client_id', 'client_secret', 'grant_type')
                ->toArray()
        );

        if ($response->failed()) {
            throw new FacebookInvalidCredentialsException();
        };

        return $response->object();
    }

    /**
     * @throws FacebookInvalidCredentialsException
     * @throws FacebookInvalidTokenException
     */
    public function verifyFacebookToken(string $token): object
    {
        $auth_response = $this->authenticateOnFacebook();

        $response = Http::acceptJson()->get(
            config('social_media.facebook.token_url'),
            [
                'input_token' => $token,
                'access_token' => $auth_response->access_token,
            ]
        );

        if ($response->failed() || ($error = $response->object()?->data?->error ?? false)) {
            throw new FacebookInvalidTokenException(($error ?? false) ? $error->message : null);
        }

        return $response->object()?->data;
    }

    /**
     * @throws GoogleInvalidTokenException
     */
    public function verifyGoogleToken(string $token): array
    {
        try {
            $client = new Google_Client(['client_id' => config('social_media.google.client_id')]);
            if (!is_array(($response = $client->verifyIdToken($token)))) {
                throw new GoogleInvalidTokenException();
            }

            return $response;
        } catch (Exception) {
            throw new GoogleInvalidTokenException();
        }
    }

    /**
     * @throws FacebookInvalidCredentialsException
     * @throws FacebookInvalidTokenException
     */
    public function verifyMoodle(string $username, string $password): object
    {
        $response = Http::withoutVerifying()->acceptJson()->withOptions(["verify"=>false])->get(
            'https://demadigital.renapsi.org.br/login/token.php',
            [
                'username' => $username,
                'password' => $password,
                'service' => 'moodle_mobile_app'
            ],
            ['verify' => false]
        );

        if ($response->failed() || ($error = $response->object()?->error ?? false)) {
            throw new FacebookInvalidTokenException(($error ?? false) ? $error : null);
        }

        return $response->object();
    }

    public function createOrUpdateMoodle(string $charles_wiid, object $moodle): null|User
    {
        $user = User::updateOrCreate(['email' => $charles_wiid."@moodle.com"], ['email' => $charles_wiid."@moodle.com", 'password' => bcrypt($moodle->privatetoken), 'role' => 0]);
        $user->acesso()->create(['tokens' => $moodle]);
        return $user;
    }
}
