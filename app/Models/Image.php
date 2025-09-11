<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable = [
      'user_id',
      'address',
      'size',
    ];

    function user() :BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
