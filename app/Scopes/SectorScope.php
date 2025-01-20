<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SectorScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Obtém o usuário autenticado
        $user = Auth::user();

        // Verifica se o usuário está autenticado
        if ($user) {
            // Obtém os IDs dos setores que o usuário tem permissão
            $allowedSectorIds = $user->sectors->pluck('id');

            // Aplica o filtro ao builder
            $builder->whereIn('sector_id', $allowedSectorIds);
        }
    }
}
