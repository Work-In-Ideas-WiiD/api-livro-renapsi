<?php

namespace App\Services\Users;

use App\Filters\Query\PostgreRelationFixer;
use App\Filters\Global\GlobalIndexFilter;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Aluno\Suporte;

class UserService
{
    public const GLOBAL_FILTER_FIELDS = [
        'id',
        'email',
        'is_admin',
    ];

    public function index(Request $request): LengthAwarePaginator
    {
        $paginate = $request->get('per_page', 15);
        $query = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::custom('id', new PostgreRelationFixer()),
                'email',
                AllowedFilter::exact('admin', 'is_admin'),
                // AllowedFilter::custom('global', new GlobalIndexFilter(self::GLOBAL_FILTER_FIELDS)),

            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                AllowedSort::field('criado_em', 'created_at'),
                AllowedSort::field('admin', 'is_admin'),
                'nome',
                'email'
            ])
            ->allowedIncludes(['piloto']);

        return $query->paginate($paginate);
    }

    public function update(Request $request, User $user): User
    {
        $validatedData = $request->safe()->all();
        if ($validatedData['password']) {
            $validatedData['password'] = Hash::make($request->safe()->password);
        }

        return tap($user)->update($validatedData);
    }

    public function createSuport(Request $request, User $user): Suporte
    {
        $suporte = $user->suporte()->create(['email' => $request->safe()->email, 'texto' => $request->safe()->texto]);
        $user->sendNewSendSuportRequestNotification($suporte->email, $suporte->texto);
        return $suporte;
    }


}
