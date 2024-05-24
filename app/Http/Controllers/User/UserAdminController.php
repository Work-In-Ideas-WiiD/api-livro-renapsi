<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Users\AdminRegisterRequest;
use App\Http\Requests\Users\AdminUpdateRegisterRequest;
use App\Http\Resources\Users\UserResources;
use App\Models\User;
use App\Services\Users\UserAdminService;
use Illuminate\Http\JsonResponse;

class UserAdminController extends BaseApiController
{
    public function __construct(private readonly UserAdminService $userAdminService)
    {
    }

    public function register(AdminRegisterRequest $request): JsonResponse
    {
        if (!$this->userAdminService->register($request)) {
            return $this->sendResponse();
        }

        return $this->sendResponse(
            'Um e-mail foi enviado com as instruções para o cadastro da senha.',
            true,
            200
        );
    }

    public function updatePrimeiroAcesso(AdminUpdateRegisterRequest $request): UserResources|JsonResponse
    {
        $user = User::where('token', $request->safe()->token)
            ->firstOrFail();

        if (!($user = $this->userAdminService->update($request, $user))) {
            return $this->sendResponse();
        }

        return new UserResources($user);
    }
}
