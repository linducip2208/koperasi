<?php

namespace App\Support\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::creating(function ($model) {
            if (empty($model->tenant_id)) {
                $model->tenant_id = CurrentTenant::id();
            }
        });

        static::addGlobalScope(new TenantScope);
    }
}

class TenantScope implements Scope
{
    public function apply(Builder $builder, $model): void
    {
        $builder->where($model->getTable() . '.tenant_id', CurrentTenant::id());
    }
}
