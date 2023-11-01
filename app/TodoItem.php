<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

class TodoItem extends Model
{

    Use SoftDeletes;

    protected $hidden = [
        'id', 'deleted_at'
    ];

    protected static function boot() {
        parent::boot();
        self::creating(function($todoInstance) {
            $todoInstance->uuid = Uuid::uuid4()->toString();
        });
    }

    public function getRouteKeyName(){
        return 'uuid';
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toFormattedDateString();
    }
}
