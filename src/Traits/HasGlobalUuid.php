<?php

namespace Submtd\LaravelGlobalUuid\Traits;

use Submtd\LaravelGlobalUuid\Models\GlobalUuid;
use Illuminate\Support\Str;

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
        });
        static::created(function ($model) {
            GlobalUuid::create([
                'uuid' => (string) Str::uuid(),
                'model_type' => get_class($model),
                'model_id' => $model->id,
            ]);
        });
    }

    public function uuid()
    {
        return $this->uuid;
    }

    public function globalUuid()
    {
        return $this->morphOne(GlobalUuid::class, 'model');
    }
}
