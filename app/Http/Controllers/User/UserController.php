<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Global\GlobalIndexRequest;
use App\Http\Requests\Users\PushNotificationStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Http\Resources\Users\UserCollection;
use App\Http\Resources\Users\UserResources;
use App\Models\User;
use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseApiController
{

    public function __construct(private readonly UserService $service)
    {
    }

    public function index(GlobalIndexRequest $request): UserCollection
    {
        return new UserCollection($this->service->index($request));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResources
    {
        return new UserResources($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): UserResources
    {
        return $this->show($this->service->update($request, $user));
    }

    public function pushNotification(PushNotificationStoreRequest $request): JsonResponse
    {
        auth()->user()->update($request->validated());
        return $this->sendResponse(is_success: true, code: Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        if (!$user->delete()) {
            return $this->sendResponse();
        }

        return $this->sendResponse(is_success: true, code: Response::HTTP_NO_CONTENT);
    }
}