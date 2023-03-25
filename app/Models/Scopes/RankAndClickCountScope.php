<?php

namespace App\Models\Scopes;

use App\Models\Click;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class RankAndClickCountScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // use laravel query builder to add rank column from clicks relation based on all users
        $clickQuery = Click::select(
            DB::raw('RANK() OVER (ORDER BY count(*) DESC) as user_rank'),
            DB::raw('count(*) as click_count'),
            'user_id'
        )
        ->whereNotNull('user_id')
        ->groupBy('user_id');

        $builder->joinSub(
            $clickQuery,
            'click_counts',
            fn ($join) => $join->on(
                $model->getTable().'.id',
                'click_counts.user_id'
            )
        );
    }
}
