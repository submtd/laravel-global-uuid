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
    /**
     * trait's boot method
     */
    public static function bootHasGlobalUuid()
    {
        // create the global uuid when creating a model
        static::created(function ($model) {
            $model->globalUuid()->create();
            $model->uuid = $model->globalUuid->uuid;
        });
        // delete the global uuid when deleting a model
        static::deleting(function ($model) {
            $model->globalUuid->delete();
        });
    }

    /**
     * polymorphic relationship to the global uuid model
     */
    public function globalUuid()
    {
        return $this->morphOne(config('laravel-global-uuid.uuid_model', GlobalUuid::class), 'model');
    }

    /**
     * uuid local query scope
     * allows $model->uuid($uuid)->first();
     */
    public function scopeUuid(Builder $builder, string $uuid)
    {
        return $builder->whereHas('globalUuid', function ($query) use ($uuid) {
            $query->where('uuid', Uuid::fromString($uuid)->getBytes());
        });
    }

    /**
     * uuidIn local query scope
     * allows $model->uuidIn($uuidArray)->get();
     */
    public function scopeUuidIn(Builder $builder, array $uuids)
    {
        $uuids = array_map(function ($uuid) {
            return Uuid::fromString($uuid)->getBytes();
        }, $uuids);
        return $builder->whereHas('globalUuid', function ($query) use ($uuids) {
            $query->whereIn('uuid', $uuids);
        });
    }

    /**
     * get uuid attribute
     * allows $model->uuid
     */
    public function getUuidAttribute()
    {
        return $this->globalUuid->uuid;
    }
}
