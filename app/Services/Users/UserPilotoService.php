<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\SocialMedia\SocialMediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserPilotoService
{
    public function __construct(private readonly SocialMediaService $socialMediaService)
    {
    }

    public function register(Request $request): User
    {
        return DB::transaction(function () use ($request) {
            $userData = $request->safe()->user;
            $userData['password'] = Hash::make(Str::random(15));
            $userData['token'] = Str::random(6);
            $user = User::create($userData);

            $user->piloto()->create($request->safe()->piloto);

            $user->sendNewPasswordRequestNotification(route('user-piloto.update-primeiro-acesso'));

            return $user;
        });
    }

    public function update(Request $request, User $user): User
    {
        $validatedData = $request->safe()->all();
        $validatedData['password'] = Hash::make($request->safe()->password);
        $validatedData['email_verified_at'] = now();
        $validatedData['token'] = Str::random(6);
        return tap($user)->update($validatedData);
    }

    public function resetPassword(User $user): User
    {
        $user = tap($user)->update(['token' => Str::random(6)]);

        $user->resetPasswordNotification(
            url: route('user-piloto.update-primeiro-acesso')
        );

        return $user;
    }
}
