<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Traits\AuthHelper;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Auth\FacebookLoginRequest;
use App\Http\Requests\Auth\SocialMediaLoginRequest;
use App\Http\Requests\SocialMedias\SocialMediaLinkRequest;
use App\Services\SocialMedia\SocialMediaService;
use App\Http\Requests\Auth\LoginMoodleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SocialMediaController extends BaseApiController
{
    use AuthHelper;

    public function __construct(private readonly SocialMediaService $socialMediaService)
    {
    }

    public function loginMoodle(LoginMoodleRequest $request): JsonResponse
    {
        $response = $this->socialMediaService->isMoodleValid($request->safe()->username, $request->safe()->password);

        if($response){
            $user = $this->socialMediaService->createOrUpdateMoodle($request->safe()->username, $response);
            return $this->respondWithToken(Auth::tokenById($user->id));
        }
        else{
            return $this->sendResponse(message: 'Login inexistente!', code: 404);
        }
        
        return $this->sendResponse(message: 'Token inválido!');
    }


    public function facebookLogin(FacebookLoginRequest $request): JsonResponse
    {
        if (!($facebook_response = $this->socialMediaService->isFacebookTokenValid($request->safe()->token))) {
            return $this->sendResponse(message: 'Token inválido!');
        }
        
        if (!($socialMedia = $this->socialMediaService->getSocialMedia(field: 'facebook_token_id', token: $facebook_response->user_id))) {
            if ($user = $this->socialMediaService->isEmailAvailable($request->safe()->email)) {
                $this->socialMediaService->createOrUpdateSocialMedias($user, ['facebook_token_id' => $facebook_response->user_id]);

                return $this->respondWithToken(Auth::tokenById($user->id));
            }

            return $this->sendResponse(message: 'Login inexistente!', code: 404);
        }

        return $this->respondWithToken(Auth::tokenById($socialMedia->user->id));
    }

    public function googleLogin(SocialMediaLoginRequest $request): JsonResponse
    {
        if (!($google_response = $this->socialMediaService->isGoogleTokenValid($request->safe()->token))) {
            return $this->sendResponse(message: 'Token inválido!');
        }

        if (!($socialMedia = $this->socialMediaService->getSocialMedia(field: 'google_token_id', token: $google_response['sub']))) {
            if ($user = $this->socialMediaService->isEmailAvailable($google_response['email'])) {
                $this->socialMediaService->createOrUpdateSocialMedias($user, ['google_token_id' => $google_response['sub']]);

                return $this->respondWithToken(Auth::tokenById($user->id));
            }

            return $this->sendResponse(message: 'Login inexistente!', code: 404);
        }

        return $this->respondWithToken(Auth::tokenById($socialMedia->user->id));
    }

    public function linkFacebook(FacebookLoginRequest $request): JsonResponse
    {
        if (($socialMedia = Auth::user()->socialMedia) && $socialMedia->facebook_token_id) {
            return $this->sendResponse(message: 'Conta já está vinculada com Facebook!');
        }

        if (!($facebook_response = $this->socialMediaService->isFacebookTokenValid($request->safe()->token))) {
            return $this->sendResponse(message: 'Token inválido!');
        }

        // if ($this->socialMediaService->isAuthenticatedUserEmailDifferentFromRequest(request_email: $request->safe()->email)) {
        //     return $this->sendResponse(message: 'E-mail já está sendo utilizado por outro usuário!');
        // }
        
        $this->socialMediaService->createOrUpdateSocialMedias(
            user: Auth::user(),
            socialMedia: ['facebook_token_id' => $facebook_response->user_id]
        );

        return $this->sendResponse(message: 'Facebook vinculado com successo!', is_success: true, code: 200);
    }

    public function linkGoogle(SocialMediaLinkRequest $request): JsonResponse
    {
        if (($socialMedia = Auth::user()->socialMedia) && $socialMedia->google_token_id) {
            return $this->sendResponse(message: 'Conta já está vinculada com Google!');
        }

        if (!($google_response = $this->socialMediaService->isGoogleTokenValid($request->safe()->token))) {
            return $this->sendResponse(message: 'Token inválido!');
        }

        if ($this->socialMediaService->isAuthenticatedUserEmailDifferentFromRequest(request_email: $google_response['email'])) {
            return $this->sendResponse(message: 'E-mail já está sendo utilizado por outro usuário!');
        }

        $this->socialMediaService->createOrUpdateSocialMedias(
            user: Auth::user(),
            socialMedia: ['google_token_id' => $google_response['sub']]
        );

        return $this->sendResponse(message: 'Google vinculado com successo!', is_success: true, code: 200);
    }

    public function unlinkFacebook(): JsonResponse
    {
        if (!Auth::user()->socialMedia?->facebook_token_id) {
            return $this->sendResponse(message: 'Facebook login id inexistente!', code: 404);
        }

        Auth::user()->socialMedia()->update(['facebook_token_id' => null]);

        return $this->sendResponse(message: 'Facebook desvinculado com successo!', is_success: true, code: 200);
    }

    public function unlinkGoogle(): JsonResponse
    {
        if (!Auth::user()->socialMedia?->google_token_id) {
            return $this->sendResponse(message: 'Google login id inexistente!', code: 404);
        }

        Auth::user()->socialMedia()->update(['google_token_id' => null]);

        return $this->sendResponse(message: 'Google desvinculado com successo!', is_success: true, code: 200);
    }
}
