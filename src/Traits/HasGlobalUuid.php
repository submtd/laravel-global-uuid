<?php

namespace Submtd\LaravelGlobalUuid\Traits;

use Submtd\LaravelGlobalUuid\Models\GlobalUuid;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Builder;

/**
 * HasGlobalUuid trait
 * Add this trait to your models classes to
 * include global uuid functionality
 */
trait HasGlobalUuid
{
    public static function bootHasGlobalUuid()
    {
        static::retrieved(function ($model) {
            $model->uuid = $model->globalUuid->uuid;
            unset($model->globalUuid);
        });
        static::created(function ($model) {
            $model->globalUuid()->create();
            $model->uuid = $model->globalUuid->uuid;
            unset($model->globalUuid);
        });
    }

    public function globalUuid()
    {
        return $this->morphOne(config('laravel-global-uuid.uuid_model', GlobalUuid::class), 'model');
    }

    public function scopeUuid(Builder $builder, string $uuid)
    {
        return $builder->whereHas('globalUuid', function ($query) use ($uuid) {
            $query->where('uuid', Uuid::fromString($uuid)->getBytes());
        });
    }
}
