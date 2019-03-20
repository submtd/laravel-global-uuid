<?php

namespace Submtd\LaravelGlobalUuid\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class GlobalUuid extends Model
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->uuid = Uuid::uuid4()->getBytes();
        });
        static::retrieved(function (Model $model) {
            $model->uuid = (string) Uuid::fromBytes($model->uuid);
        });
    }
}
