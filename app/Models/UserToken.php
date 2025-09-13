<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class UserToken extends Model
{
    protected $keyType = 'primary';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
      'id',
      'user_id',
      'access_token',
      'expires',
    ];

    protected static function boot(){

        parent::boot();

        static::creating(function($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
