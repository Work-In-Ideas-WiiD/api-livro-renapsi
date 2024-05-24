<?php

namespace App\Services\Users;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserAdminService
{
    public function index(): LengthAwarePaginator
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters([
                'email',
                'nome',
            ])
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'nome', 'email')
            ->admin()
            ->paginate();
    }

    public function register(Request $request): User
    {
        $validatedData = $request->safe()->all();
        $validatedData['password'] = Hash::make(Str::random(15));
        $validatedData['token'] = Str::random(6);
        $validatedData['is_admin'] = Roles::Admin->value;

        return DB::transaction(function () use ($validatedData) {
            $user = User::create($validatedData);
            $user->sendNewPasswordRequestNotification(route('admin.update-primeiro-acesso'));
            return $user;
        });
    }

    public function update(Request $request, User $user): User
    {
        $validatedData = $request->safe()->all();
        $validatedData['password'] = Hash::make($request->safe()->password);
        $validatedData['email_verified_at'] = now();
        return tap($user)->update($validatedData);
    }
}
