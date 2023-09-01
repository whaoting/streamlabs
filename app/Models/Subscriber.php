<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, int, int, int, boolean, datetime, datetime>
     */
    protected $fillable = [
        'id',
        'user_id',
        'target_user_id',
        'tier',
        'is_read',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<int, int, int, int, boolean, datetime, datetime>
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'target_user_id' => 'integer',
        'tier' => 'integer',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
