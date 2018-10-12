<?php

namespace Submtd\LaravelGlobalUuid\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalUuid extends Model
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'model_type',
        'model_id',
    ];
}
