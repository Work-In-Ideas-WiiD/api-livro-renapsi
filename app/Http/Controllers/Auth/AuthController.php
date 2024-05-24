<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Traits\AuthHelper;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\RefreshDeviceRequest;
use App\Http\Resources\Users\UserResources;
use App\Models\User;
use App\Services\Users\UserClienteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseApiController
{
    use AuthHelper;

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!($token = Auth::attempt($request->safe()->all()))) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return UserResources|JsonResponse
     */
    public function me(): UserResources|JsonResponse
    {
        return new UserResources(
            User::with(
                'piloto',
                'piloto.equipe'
            )->find(Auth::id())
        );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh_device(RefreshDeviceRequest $request): JsonResponse
    {
        $user = User::where($request->safe()->all())->where('is_admin', false)->first();

        if($user)
        {
            Auth::login($user);
            return $this->respondWithToken(Auth::refresh());
        }

        return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    public function resetPassword(ResetPasswordRequest $request, UserClienteService $service): JsonResponse
    {
        $user = User::where('email', $request->safe()->email)->first();
        $service->resetPassword(user: $user);

        return $this->sendResponse(
            'Um e-mail foi enviado com as instruções para a redefinição da senha.',
            true,
            200
        );
    }
}
