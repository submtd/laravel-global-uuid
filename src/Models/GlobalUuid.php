<?php

namespace Submtd\LaravelGlobalUuid\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Watson\Rememberable\Rememberable;

class GlobalUuid extends Model
{
    use Rememberable;

    /**
     * @var $rememberCacheTag
     * tag for the rememberable trait
     */
    public $rememberCacheTag = 'global_uuid_queries';

    /**
     * @var $primaryKey
     * sets the primary key on the table to be uuid
     */
    protected $primaryKey = 'uuid';

    /**
     * @var $incrementing
     * since we're using uuids instead of incrementing integers, this nees to be false
     */
    public $incrementing = false;

    /**
     * @var $timestamps
     * no point in storing timestamps in this table
     */
    public $timestamps = false;

    /**
     * boot method
     */
    public static function boot()
    {
        parent::boot();
        // generate a uuid when creating the model
        static::creating(function (Model $model) {
            $model->uuid = Uuid::uuid4()->getBytes();
        });
        // convert the uuid from binary to string when retrieving the model
        static::retrieved(function (Model $model) {
            $model->uuid = (string) Uuid::fromBytes($model->uuid);
        });
    }

    /**
     * polymorphic relationship for model
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * uuid local scope
     * allows $model->uuid($uuid)->first();
     */
    public function scopeUuid(Builder $builder, string $uuid)
    {
        return $builder->where('uuid', Uuid::fromString($uuid)->getBytes());
    }

    /**
     * uuidIn local scope
     * allows $model->uuidIn($uuidArray)->get();
     */
    public function scopeUuidIn(Builder $builder, array $uuids)
    {
        $uuids = array_map(function ($uuid) {
            return Uuid::fromString($uuid)->getBytes();
        }, $uuids);
        return $builder->whereIn('uuid', $uuids);
    }
}
