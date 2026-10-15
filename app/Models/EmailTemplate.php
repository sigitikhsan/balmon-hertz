<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';
    public const NEED_REVISION = 'need_revision';

    protected $fillable = [
        'key',
        'subject',
        'body',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
